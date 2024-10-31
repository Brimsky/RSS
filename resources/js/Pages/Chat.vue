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
            @scroll="onScroll"
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
import { ref, onMounted, onUnmounted, computed, nextTick } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
const sellerId = props.seller;
const users = ref([]);
const selectedUser = ref(null);
const messages = ref([]);
const newMessage = ref('');
const currentUser = ref(null);
const searchQuery = ref('');
let messageUpdateInterval = null;
let isLoadingOldMessages = ref(false);
const lastMessageId = ref(null); // Track the ID of the last loaded message for pagination
const messagesContainer = ref(null); // Ref for the messages container

const filteredUsers = computed(() => {
  return users.value.filter(user => 
    user.id !== currentUser.value.id
  );
});

// Load users from the backend
const loadUsers = async () => {
  try {
    const response = await fetch('/api/users');
    users.value = await response.json();
  } catch (error) {
    console.error('Ошибка при загрузке пользователей:', error);
  }
};

// Load messages with optional pagination for older messages
const loadMessages = async (userId, loadOlder = false) => {
  try {
    if (isLoadingOldMessages.value) return; // Prevent duplicate loads during scrolling

    isLoadingOldMessages.value = true;

    // Build the URL with optional `before_message_id` for loading older messages
    let url = `/api/messages?receiver_id=${userId}`;
    if (loadOlder && lastMessageId.value) {
      url += `&before_message_id=${lastMessageId.value}`;
    }

    const response = await fetch(url);

    if (!response.ok) {
      throw new Error('Ошибка при загрузке сообщений');
    }

    const data = await response.json();

    if (data.length) {
      if (loadOlder) {
        // Prepend older messages to the existing list
        messages.value = [...data, ...messages.value];
      } else {
        // First time load
        messages.value = data;
      }

      // Update the lastMessageId to the oldest message's ID
      lastMessageId.value = data[0]?.id;
    }
  } catch (error) {
    console.error('Ошибка загрузки сообщений:', error);
  } finally {
    isLoadingOldMessages.value = false;
  }
};

// Triggered when a user is selected from the user list
const selectUser = async (user) => {
  selectedUser.value = user;
  messages.value = []; // Clear previous messages
  lastMessageId.value = null; // Reset message pagination
  await loadMessages(user.id); // Load initial messages
};

// Send a new message to the backend and update the chat window
const sendMessage = async () => {
  if (!newMessage.value.trim() || !selectedUser.value) {
    return; // Prevent sending empty messages
  }

  const messageData = {
    receiver_id: selectedUser.value.id,
    message: newMessage.value.trim(),
  };

  try {
    const response = await fetch('/api/messages', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
      },
      body: JSON.stringify(messageData),
    });

    if (!response.ok) {
      throw new Error('Ошибка отправки сообщения');
    }

    const message = await response.json();
    messages.value.push(message);
    newMessage.value = '';

    await nextTick(); // Wait for DOM updates
    scrollToBottom(); // Scroll to the bottom of the chat window
  } catch (error) {
    console.error('Ошибка отправки сообщения:', error);
  }
};

// Scroll event listener for detecting when the user reaches the top
const onScroll = async () => {
  const container = messagesContainer.value;
  if (container.scrollTop === 0 && !isLoadingOldMessages.value) {
    // Load older messages when user scrolls to the top
    await loadMessages(selectedUser.value.id, true);
  }
  }
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

