<template>
    <AuthenticatedLayout>
      <div class="chat-container flex h-screen">
          <!-- Left Sidebar - User List -->
          <div class="user-list w-1/4 border-r border-gray-300 p-4 bg-gray-100 overflow-y-auto">
              <input 
                  v-model="searchQuery" 
                  type="text" 
                  placeholder="Search users..." 
                  class="w-full p-2 mb-4 border rounded-lg focus:outline-none focus:border-purple-500"
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
                      class="user-item p-3 rounded-lg cursor-pointer hover:bg-gray-200 flex items-center space-x-3 transition-all duration-200"
                      :class="{'bg-gray-200': selectedUser?.id === user.id}"
                  >
                      <!-- User Avatar -->
                      <div class="relative">
                          <div v-if="user.avatar_url" class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
                              <img 
                                  :src="user.avatar_url" 
                                  :alt="user.name"
                                  class="w-full h-full object-cover"
                              />
                          </div>
                          <div v-else class="w-12 h-12 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center flex-shrink-0">
                              <span class="text-white font-semibold text-lg">
                                  {{ user.name.charAt(0).toUpperCase() }}
                              </span>
                          </div>
                          <!-- Online Status Indicator -->
                          <div 
                              class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white"
                              :class="user.online ? 'bg-green-500' : 'bg-gray-400'"
                          ></div>
                      </div>
  
                      <!-- User Info -->
                      <div class="flex-grow min-w-0">
                          <div class="flex justify-between items-start">
                              <div class="font-medium truncate">{{ user.name }}</div>
                              <div v-if="user.last_message" class="text-xs text-gray-400 flex-shrink-0 ml-2">
                                  {{ formatTime(user.last_message.created_at) }}
                              </div>
                          </div>
                          <div v-if="user.last_message" class="text-sm text-gray-500 truncate mt-1">
                              <span v-if="user.last_message.is_own" class="text-purple-600">You: </span>
                              {{ user.last_message.message }}
                          </div>
                          <div v-else-if="user.role" 
                               class="text-xs px-2 py-1 bg-gray-200 rounded-full text-gray-600 inline-block mt-1"
                          >
                              {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                          </div>
                      </div>
                  </div>
              </div>
          </div>
  
          <!-- Right Side - Chat Area -->
          <div class="w-3/4 flex flex-col h-full">
              <div v-if="selectedUser" class="flex flex-col h-full">
                  <!-- Chat Header -->
                  <div class="p-4 border-b flex items-center space-x-3 bg-white">
                      <div class="relative">
                          <div v-if="selectedUser?.avatar_url" class="w-10 h-10 rounded-full overflow-hidden">
                              <img 
                                  :src="selectedUser.avatar_url" 
                                  :alt="selectedUser.name"
                                  class="w-full h-full object-cover"
                              />
                          </div>
                          <div v-else class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                              <span class="text-white font-semibold">
                                  {{ selectedUser?.name.charAt(0).toUpperCase() }}
                              </span>
                          </div>
                          <!-- Online Status Indicator -->
                          <div 
                              class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white"
                              :class="selectedUser.online ? 'bg-green-500' : 'bg-gray-400'"
                          ></div>
                      </div>
                      <div>
                          <h2 class="text-xl font-semibold">{{ selectedUser.name }}</h2>
                          <p class="text-sm text-gray-500">
                              {{ selectedUser.online ? 'Online' : 'Offline' }}
                          </p>
                      </div>
                  </div>
  
                  <!-- Messages Area -->
                    <div class="flex-1 flex flex-col overflow-hidden">
                        <!-- Product Preview -->
                        <div v-if="productInfo" class="p-4 border-b">
                            <div class="bg-white shadow-sm rounded-lg">
                                <div class="p-3">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-16 h-16 flex-shrink-0">
                                            <img
                                                :src="productInfo.photo || '/placeholder.png'"
                                                :alt="productInfo.name"
                                                class="w-full h-full object-cover rounded-lg"
                                            />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-medium text-gray-900 truncate">
                                                {{ productInfo.name }}
                                            </h4>
                                            <p class="text-sm font-semibold text-purple-600">
                                                ${{ Number(productInfo.price).toFixed(2) }}
                                            </p>
                                            <p class="text-xs text-gray-500 line-clamp-2">
                                                {{ productInfo.description }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Messages Container -->
                        <div class="flex-1 overflow-y-auto" ref="messagesContainer">
                            <div class="flex flex-col justify-end min-h-full p-4 space-y-4">
                                <template v-for="(message, index) in sortedMessages" :key="message.id">
                                    <!-- Product Preview -->
                                    <div v-if="message.is_first_message && message.product_data" class="mb-4">
                                        <div class="bg-white shadow-sm rounded-lg">
                                            <div class="p-3">
                                                <div class="flex items-start space-x-3">
                                                    <div class="w-16 h-16 flex-shrink-0">
                                                        <img
                                                            :src="getProductImage(message.product_data)"
                                                            :alt="getProductName(message.product_data)"
                                                            class="w-full h-full object-cover rounded-lg"
                                                            @error="$event.target.src = '/placeholder.png'"
                                                        />
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <h4 class="font-medium text-gray-900 truncate">
                                                            {{ getProductName(message.product_data) }}
                                                        </h4>
                                                        <p class="text-sm font-semibold text-purple-600">
                                                            ${{ formatPrice(getProductPrice(message.product_data)) }}
                                                        </p>
                                                        <p class="text-xs text-gray-500 line-clamp-2">
                                                            {{ getProductDescription(message.product_data) }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Messages -->
                                    <div 
                                        class="flex items-end mb-4"
                                        :class="message.isCurrentUser ? 'flex-row-reverse' : 'flex-row'"
                                    >
                                        <div class="flex-shrink-0 mx-2">
                                            <div 
                                                v-if="message.isCurrentUser ? currentUser?.avatar_url : message.sender?.avatar_url" 
                                                class="w-8 h-8 rounded-full overflow-hidden"
                                            >
                                                <img 
                                                    :src="message.isCurrentUser ? currentUser.avatar_url : message.sender?.avatar_url"
                                                    :alt="message.isCurrentUser ? currentUser.name : message.sender?.name"
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <div v-else class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-blue-500 flex items-center justify-center">
                                                <span class="text-white text-sm font-semibold">
                                                    {{ (message.isCurrentUser ? currentUser?.name : message.sender?.name)?.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>

                                        <div 
                                            class="max-w-[60%] flex flex-col"
                                            :class="message.isCurrentUser ? 'items-end' : 'items-start'"
                                        >
                                            <div 
                                                class="px-4 py-2 rounded-lg shadow-sm"
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
  
                      <!-- Message Input Area -->
                      <div class="border-t bg-white p-4">
                        <div class="flex items-center gap-3">
                        <!-- Message Input -->
                        <input 
                            v-model="newMessage"
                            @keypress.enter="sendMessage"
                            type="text"
                            placeholder="Type a message..."
                            class="flex-1 p-3 bg-gray-50 rounded-full border-0 focus:ring-2 focus:ring-purple-500 focus:outline-none transition-all duration-200 placeholder-gray-400"
                        />
                        
                        <!-- Send Button -->
                        <button 
                            @click="sendMessage"
                            :disabled="!newMessage.trim()"
                            class="p-3 rounded-full bg-gradient-to-r from-purple-600 to-blue-500 hover:from-purple-500 hover:to-blue-400 transition-all duration-300 flex items-center justify-center min-w-[48px] disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            viewBox="0 0 24 24" 
                            fill="none" 
                            stroke="currentColor" 
                            class="w-5 h-5 text-white transform rotate-45"
                            >
                            <path 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2" 
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                            />
                            </svg>
                        </button>
                        </div>
                    </div>
                  </div>
              </div>
              <!-- Empty State -->
              <div v-else class="flex-1 flex items-center justify-center text-gray-500">
                  <div class="text-center">
                      <div class="w-16 h-16 mx-auto mb-4 text-gray-400">
                          <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                          </svg>
                      </div>
                      <p class="text-lg font-medium">Please select a user to start chatting</p>
                  </div>
              </div>
          </div>
      </div>
    </AuthenticatedLayout>
  </template>

<script setup>
import { ref, reactive, onMounted, nextTick, computed, watch } from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { router, usePage } from '@inertiajs/vue3';
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

const page = usePage();
const loading = ref(true);
const error = ref(null);
const users = ref([]);
const selectedUser = ref(null);
const currentUser = ref(null);
const messages = ref([]);
const newMessage = ref('');
const searchQuery = ref('');
const productInfo = ref(null);

onMounted(async () => {
    try {
        loading.value = true;
        
        const userResponse = await axios.get('/api/user');
        currentUser.value = userResponse.data;

        initializeWebSockets();

        const usersResponse = await axios.get('/chat/users');
        users.value = usersResponse.data;

        const urlParams = new URLSearchParams(window.location.search);
        const sellerId = urlParams.get('sellerId');
        const encodedProduct = urlParams.get('product');
    
        console.log('URL Parameters:', {
            sellerId,
            encodedProduct
        });

        if (encodedProduct) {
            try {
                const decodedProduct = decodeURIComponent(encodedProduct);
                console.log('Decoded product string:', decodedProduct);
                
                productInfo.value = JSON.parse(decodedProduct);
                console.log('Parsed product info:', productInfo.value);
            } catch (err) {
                console.error('Error parsing product info:', err);
            }
        }

        if (sellerId) {
            let seller = users.value.find(u => u.id === parseInt(sellerId));
            
            if (!seller) {
                try {
                    const sellerResponse = await axios.get(`/chat/users/${sellerId}`);
                    seller = sellerResponse.data;
                    if (!users.value.some(u => u.id === seller.id)) {
                        users.value.unshift(seller); 
                    }
                } catch (err) {
                    console.error('Error fetching seller data:', err);
                    return;
                }
            }

            if (seller) {
                await selectUser(seller);
            }
        }

    } catch (err) {
        console.error('Error initializing chat:', err);
        error.value = 'Failed to initialize chat';
    } finally {
        loading.value = false;
    }
});

// onUnmounted(() => {
   // if (currentUser.value?.id) {
      // window.Echo.leave(`chat.${currentUser.value.id}`);
       // window.Echo.leave('users.status');
    // }
// });

watch(() => messages.value?.length, (newLength, oldLength) => {
    if (newLength > oldLength) {
        scrollToBottom();
    }
});

const sortedMessages = computed(() => {
  if (!messages.value) return [];
  return [...messages.value].sort((a, b) => {
    return new Date(a.created_at) - new Date(b.created_at);
  });
});

const initializeWebSockets = () => {
    if (!currentUser.value?.id) return;

    window.Echo.private(`chat.${currentUser.value.id}`)
        .listen('MessageSent', (event) => {
            console.log('New message received:', event);
            handleNewMessage(event.message);
            
            if (event.message.sender.id !== currentUser.value.id) {
                playNotificationSound();
            }
        });
        
    window.Echo.private('users.status')
        .listen('UserStatusUpdated', (event) => {
            const userIndex = users.value.findIndex(u => u.id === event.userId);
            if (userIndex !== -1) {
                users.value[userIndex].online = event.online;
            }
        });
};

const selectUser = async (user) => {
    try {
        if (!user) {
            console.error('No user provided to selectUser');
            return;
        }
        
        selectedUser.value = user;
        messages.value = []; 
        
        const response = await axios.get(`/chat/user/${user.id}`);
        console.log('Received messages:', response.data);
        
        if (Array.isArray(response.data)) {
            messages.value = response.data.map(message => ({
                ...message,
                isCurrentUser: message.user_id === currentUser.value.id
            }));
        }
        
        console.log('Processed messages:', messages.value);
        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Error in selectUser:', error);
        messages.value = [];
    }
};

const loadMessages = async () => {
    if (!selectedUser.value?.id) {
        return;
    }

    try {
        const response = await axios.get(`/chat/user/${selectedUser.value.id}`);
        messages.value = response.data.map(message => ({
            ...message,
            isCurrentUser: message.user_id === currentUser.value.id,
            product_data: message.product_data ? JSON.parse(message.product_data) : null
        }));
        
        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Load messages error:', error);
        messages.value = [];
    }
};

const handleNewMessage = (message) => {
  if (!messages.value) {
    messages.value = [];
  }
  
  if (selectedUser.value && 
      (message.sender.id === selectedUser.value.id || 
       message.recipient_id === selectedUser.value.id)) {
    messages.value.push({
      ...message,
      isCurrentUser: message.sender.id === currentUser.value.id
    });
    scrollToBottom();
  }
  
  const userIndex = users.value.findIndex(u => 
    u.id === message.sender.id || u.id === message.recipient_id
  );
  
  if (userIndex !== -1) {
    const user = users.value[userIndex];
    user.last_message = {
      message: message.message,
      created_at: message.created_at,
      is_own: message.sender.id === currentUser.value.id
    };
    users.value.splice(userIndex, 1);
    users.value.unshift(user);
  }
};

const filteredUsers = computed(() => {
    if (!users.value) return [];
    return users.value.filter(user => 
        user.name.toLowerCase().includes(searchQuery.value?.toLowerCase() || '')
    );
});

const scrollToBottom = () => {
    nextTick(() => {
        const container = document.querySelector('.overflow-y-auto');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    });
};

const sendMessage = async () => {
    if (!newMessage.value?.trim() || !selectedUser.value?.id) {
        return;
    }

    try {
        const isFirstMessage = !messages.value || messages.value.length === 0;

        const messageData = {
            message: newMessage.value,
            recipient_id: selectedUser.value.id,
            product_data: productInfo.value 
        };

        const response = await axios.post('/chat', messageData);
        
        if (!messages.value) {
            messages.value = [];
        }

        messages.value.push({
            ...response.data,
            isCurrentUser: true,
        });
        
        newMessage.value = '';

        if (isFirstMessage) {
            productInfo.value = null;
        }
        
        await nextTick();
        scrollToBottom();
    } catch (error) {
        console.error('Send message error:', error);
    }
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

const playNotificationSound = () => {
    const audio = new Audio('/notification.mp3');
    audio.play().catch(e => console.log('Error playing notification:', e));
};

const selectUserMobile = (user) => {
  selectUser(user);
  if (window.innerWidth < 768) {
    showSidebar.value = false;
  }
};

// const props = defineProps({
//     product: {
//         type: Object,
//         required: false,
//         default: null
//     }
// });

const getProductImage = (productData) => {
    try {
        const data = typeof productData === 'string' ? JSON.parse(productData) : productData;
        return data?.photo || '/placeholder.png';
    } catch (e) {
        console.error('Error parsing product image:', e);
        return '/placeholder.png';
    }
};

const getProductName = (productData) => {
    try {
        const data = typeof productData === 'string' ? JSON.parse(productData) : productData;
        return data?.name || 'Product';
    } catch (e) {
        console.error('Error parsing product name:', e);
        return 'Product';
    }
};

const getProductPrice = (productData) => {
    try {
        const data = typeof productData === 'string' ? JSON.parse(productData) : productData;
        return data?.price || 0;
    } catch (e) {
        console.error('Error parsing product price:', e);
        return 0;
    }
};

const formatPrice = (price) => {
    return Number(price).toFixed(2);
};

const getProductDescription = (productData) => {
    try {
        const data = typeof productData === 'string' ? JSON.parse(productData) : productData;
        return data?.description || '';
    } catch (e) {
        console.error('Error parsing product description:', e);
        return '';
    }
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
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background-color: rgba(115, 61, 240, 0.5);
    border-radius: 20px;
}

:global(body) {
  overflow: hidden;
}
</style>