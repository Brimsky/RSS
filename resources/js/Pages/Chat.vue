<template>
  <AuthenticatedLayout>
    <div class="chat-container flex h-full">
      <div class="user-list w-1/4 border-r border-gray-300 p-4 bg-gray-100">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search users..." 
          class="w-full p-2 mb-4 border rounded"
        />

        <div class="users-list space-y-2">
          <div 
            v-for="user in filteredUsers" 
            :key="user.id"
            @click="selectUser(user)"
            class="user-item p-2 rounded cursor-pointer hover:bg-gray-200"
            :class="{'bg-gray-200': selectedUser?.id === user.id}"
          >
            {{ user.name }}
          </div>
        </div>
      </div>

      <div class="chat-window w-3/4 p-4 flex flex-col">
        <div v-if="selectedUser" class="flex-1 flex flex-col h-full">
          <div class="chat-header pb-4 border-b">
            <h2 class="text-xl font-semibold">Chat with {{ selectedUser.name }}</h2>
          </div>

          <div 
            class="messages-container flex-1 overflow-y-auto py-4 space-y-4" 
            ref="messagesContainer"
          >
            <div 
              v-for="message in messages" 
              :key="message.id" 
              class="message-wrapper flex"
              :class="message.sender_id === currentUser.id ? 'justify-end' : 'justify-start'"
            >
              <div 
                class="message max-w-[70%] p-3 rounded-lg"
                :class="message.sender_id === currentUser.id ? 
                  'bg-blue-500 text-white ml-auto' : 
                  'bg-gray-200 text-gray-800'"
              >
                {{ message.message }}
              </div>
            </div>
          </div>

          <div class="chat-input mt-4 flex gap-2">
            <input 
              v-model="newMessage"
              @keypress.enter="sendMessage"
              placeholder="Type a message..."
              class="flex-1 p-2 border rounded-lg focus:outline-none focus:border-blue-500"
            />
            <button 
              @click="sendMessage"
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
            >
              Send
            </button>
          </div>
        </div>
        <div v-else class="flex-1 flex items-center justify-center text-gray-500">
          Please select a user to start chatting
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted, nextTick, computed } from 'vue';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from 'axios';

const messages = ref([]);
const newMessage = ref('');
const selectedUser = ref(null);
const searchQuery = ref('');
const users = ref([]);
const currentUser = reactive({ id: 1, name: 'Current User' });

// Initialize Echo with Pusher and Vite environment variables
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

onMounted(() => {
    axios.get('/chat/users').then(response => {
      users.value = response.data;
    });

    axios.get('/chat/messages').then(response => {
      messages.value = response.data;
    });

    // Listen for MessageSent event on the 'chat' channel
    window.Echo.channel('chat')
        .listen('MessageSent', (event) => {
            messages.value.push(event.message);
            scrollToBottom();
        });
});

const sendMessage = () => {
    if (newMessage.value.trim() === '') return;

    axios.post('/chat', {
      message: newMessage.value
    }, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(() => {
        newMessage.value = '';
    }).catch(error => {
        console.error(error);
    });
};

const selectUser = (user) => {
  selectedUser.value = user;
  loadMessages();
};

const loadMessages = () => {
  if (selectedUser.value) {
    axios.get(`/chat/user/${selectedUser.value.id}`).then(response => {
      messages.value = response.data;
      scrollToBottom();
    }).catch(error => {
      console.error(error);
    });
  }
};

const filteredUsers = computed(() => {
  return users.value.filter(user => 
    user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  );
});

const scrollToBottom = () => {
  nextTick(() => {
    const container = document.querySelector('.messages-container');
    if (container) container.scrollTop = container.scrollHeight;
  });
};
</script>

<style scoped>
.chat-container {
  height: calc(100vh - 64px);
}

.user-list {
  height: 100%;
  overflow-y: auto;
}

.chat-window {
  height: 100%;
}

.messages-container {
  height: 400px;
  overflow-y: auto;
}
</style>