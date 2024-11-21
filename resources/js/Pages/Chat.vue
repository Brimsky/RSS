<template>
  <AuthenticatedLayout>
    <div class="chat-container flex h-screen">
        <div class="user-list w-1/4 border-r border-gray-300 p-4 bg-gray-100 overflow-y-auto">
            <input 
                v-model="searchQuery" 
                type="text" 
                placeholder="Search users..." 
                class="w-full p-2 mb-4 border rounded"
            />

            <div v-if="loading" class="p-4 text-center text-gray-500">
                Loading users...
            </div>
            
            <div v-else-if="error" class="p-4 text-center text-red-500">
                Error: {{ error }}
            </div>
            
            <div v-else class="users-list space-y-2">
                <div v-if="filteredUsers.length === 0" class="p-2 text-gray-500">
                    No users found
                </div>
                <div v-else
                    v-for="user in filteredUsers" 
                    :key="user.id"
                    @click="selectUser(user)"
                    class="user-item p-2 rounded cursor-pointer hover:bg-gray-200 flex items-center space-x-3"
                    :class="{'bg-gray-200': selectedUser?.id === user.id}"
                >
                    <div v-if="user.avatar_url" class="w-10 h-10 rounded-full overflow-hidden flex-shrink-0">
                        <img 
                            :src="user.avatar_url" 
                            :alt="user.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div v-else class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center flex-shrink-0">
                        <span class="text-gray-600 font-semibold">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </span>
                    </div>

                    <div class="flex-grow">
                        <div class="font-medium">{{ user.name }}</div>
                        <div class="text-sm text-gray-500">{{ user.role }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-3/4 flex flex-col h-full">
            <div v-if="selectedUser" class="flex flex-col h-full">
                <div class="p-4 border-b flex items-center space-x-3">
                    <div v-if="selectedUser?.avatar_url" class="w-10 h-10 rounded-full overflow-hidden">
                        <img 
                            :src="selectedUser.avatar_url" 
                            :alt="selectedUser.name"
                            class="w-full h-full object-cover"
                        />
                    </div>
                    <div v-else class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 font-semibold">
                            {{ selectedUser?.name.charAt(0).toUpperCase() }}
                        </span>
                    </div>
                    <h2 class="text-xl font-semibold">{{ selectedUser.name }}</h2>
                </div>

                <div class="flex-1 flex flex-col overflow-hidden">
                    <div class="flex-1 overflow-y-auto" ref="messagesContainer">
                        <div class="flex flex-col p-4">
                            <template v-for="message in messages" :key="message.id">
                                <div 
                                    class="flex items-end mb-4"
                                    :class="message.isCurrentUser ? 'flex-row-reverse' : 'flex-row'"
                                >
                                    <div class="flex-shrink-0 mx-2">
                                        <div 
                                            v-if="message.isCurrentUser ? currentUser.avatar_url : message.sender?.avatar_url" 
                                            class="w-8 h-8 rounded-full overflow-hidden"
                                        >
                                            <img 
                                                :src="message.isCurrentUser ? currentUser.avatar_url : message.sender?.avatar_url"
                                                :alt="message.isCurrentUser ? currentUser.name : message.sender?.name"
                                                class="w-full h-full object-cover"
                                            />
                                        </div>
                                        <div v-else class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-gray-600 text-sm font-semibold">
                                                {{ (message.isCurrentUser ? currentUser.name : message.sender?.name)?.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                    </div>

                                    <div 
                                        class="max-w-[60%] flex flex-col"
                                        :class="message.isCurrentUser ? 'items-end' : 'items-start'"
                                    >
                                        <div 
                                            class="px-4 py-2 rounded-lg"
                                            :class="message.isCurrentUser ? 
                                                'bg-gradient-to-r from-purple-600 to-blue-500 text-white' : 
                                                'bg-gray-200 text-gray-800'"
                                        >
                                            {{ message.message }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 px-2">
                                            {{ formatTime(message.created_at) }}
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="p-4 border-t bg-white">
                        <div class="flex gap-2">
                            <input 
                                v-model="newMessage"
                                @keypress.enter="sendMessage"
                                placeholder="Type a message..."
                                class="flex-1 p-2 border rounded-lg focus:outline-none focus:border-blue-500"
                            />
                            <button 
                                @click="sendMessage"
                                class="px-4 py-2 text-white rounded-lg bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 transition-all duration-300"
                            >
                                Send
                            </button>
                        </div>
                    </div>
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
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from 'axios';
window.axios = axios;

axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    wsHost: window.location.hostname,
    wsPort: 6001,
    wssPort: 6001,
    forceTLS: false,
    encrypted: false,
    enabledTransports: ['ws', 'wss'],
    disableStats: true
});

const messages = ref([]);
const newMessage = ref('');
const selectedUser = ref(null);
const searchQuery = ref('');
const users = ref([]);
const currentUser = ref(null);
const loading = ref(true);
const error = ref(null);

onMounted(async () => {
    try {
        loading.value = true;
        
        const userResponse = await axios.get('/api/user');
        currentUser.value = userResponse.data;
        console.log('Current user:', currentUser.value);

        const usersResponse = await axios.get('/chat/users');
        users.value = usersResponse.data;
        console.log('Users loaded:', users.value);

        window.Echo.private(`chat.${currentUser.value.id}`)
            .listen('MessageSent', (e) => {
                console.log('New message received:', e);
                if (!messages.value) {
            messages.value = [];
        }
        
        const message = {
            ...e.message,
            isCurrentUser: false
        };
        messages.value.push(message);
        scrollToBottom();
    });

    } catch (error) {
        console.error('Error initializing chat:', error);
    } finally {
        loading.value = false;
    }
});

const sendMessage = async () => {
    if (newMessage.value.trim() === '' || !selectedUser.value) {
        console.log('Message empty or no user selected');
        return;
    }

    try {
        const response = await axios.post('/chat', {
            message: newMessage.value,
            recipient_id: selectedUser.value.id
        });
        
        if (!messages.value) {
            messages.value = [];
        }

        messages.value.push(response.data);
        newMessage.value = '';
        await loadMessages();
        scrollToBottom();
    } catch (error) {
        console.error('Send message error:', error);
    }
};

const selectUser = async (user) => {
    console.log('Selecting user:', user);
    selectedUser.value = user;
    await loadMessages();
};

const loadMessages = async () => {
    if (!selectedUser.value) {
        console.log('No user selected');
        return;
    }

    try {
        console.log('Loading messages for user:', selectedUser.value);
        const response = await axios.get(`/chat/user/${selectedUser.value.id}`);
        messages.value = response.data || [];
        console.log('Messages loaded:', messages.value);
        scrollToBottom();
    } catch (error) {
        console.error('Load messages error:', error);
        messages.value = [];
    }
};

const filteredUsers = computed(() => {
    if (!users.value) return [];
    return users.value.filter(user => 
        user.name.toLowerCase().includes((searchQuery.value || '').toLowerCase())
    );
});

const scrollToBottom = () => {
    nextTick(() => {
        const container = document.querySelector('.overflow-y-auto');
        if (container) {
            container.scrollTop = 0; 
        }
    });
};


const formatTime = (timestamp) => {
    if (!timestamp) return '';
    
    const date = new Date(timestamp);
    const now = new Date();
    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);
    
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const time = `${hours}:${minutes}`;
    
    if (date.toDateString() === now.toDateString()) {
        return time;
    }
    
    if (date.toDateString() === yesterday.toDateString()) {
        return `Yesterday ${time}`;
    }
    
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    return `${day}.${month} ${time}`;
};
</script>

<style scoped>
.chat-container {
    height: calc(100vh - 64px);
}

.messages-container {
    flex: 1;
    display: flex;
    flex-direction: column-reverse;
    overflow-y: auto;
}

.message {
    word-break: break-word;
}

.overflow-y-auto {
    scrollbar-width: thin;
    scrollbar-color: rgba(146, 95, 214, 0.5) transparent;
}

.overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: rgba(115, 61, 240, 0.5);
    border-radius: 20px;
    border: transparent;
}
</style>