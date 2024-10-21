<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Seller Rating and Active Listings -->
                    <div class="space-y-8">
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-bold">Your rating</h2>
                                <div class="flex items-center">
                                    <span class="text-4xl font-bold mr-2">0</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex items-center space-x-6">
                                <div class="text-center bg-gray-100 p-4 rounded-lg">
                                    <div class="text-5xl font-bold mb-2">{{ products.length }}</div>
                                    <div class="text-lg">Active</div>
                                </div>
                                <div class="space-y-4 flex-grow">
                                    <!-- Показываем кнопки создания и управления только для seller -->
                                    <template v-if="$page.props.auth.user.role === 'seller'">
                                        <a href="products/create">
                                            <button class="w-full flex items-center justify-center space-x-2 bg-blue-500 text-white px-6 py-3 rounded-lg text-lg hover:bg-blue-600 transition duration-300">
                                                <span class="text-2xl">+</span>
                                                <span>Create new listing</span>
                                            </button>
                                        </a>
                                        <button class="w-full flex items-center justify-center space-x-2 bg-gray-200 text-gray-700 px-6 py-3 rounded-lg text-lg hover:bg-gray-300 transition duration-300">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span>Inactive listings</span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6 relative">
                            <div class="space-y-6 mb-16">
                                <div v-for="(product, index) in limitedProducts" :key="product.id" class="flex items-center space-x-4 border-b pb-4 last:border-b-0 last:pb-0">
                                    <img src="https://via.placeholder.com/100" alt="Product Image" class="w-24 h-24 object-cover rounded-lg">
                                    <div class="flex-grow">
                                        <h3 class="text-xl font-semibold">
                                            <a @click="registerClick(product.id)" :href="`/products/${product.id}`" class="text-blue-600 hover:underline">
                                                {{ product.name }}
                                            </a>
                                        </h3>
                                        <p class="text-2xl font-bold text-gray-700">${{ parseFloat(product.price).toFixed(2) }}</p>
                                        <p class="text-gray-500">{{ product.description }}</p>
                                    </div>
                                    <!-- Для seller показываем кнопку "Mark as sold", для buyer - кнопку "Buy" -->
                                    <template v-if="$page.props.auth.user.role === 'seller'">
                                        <button class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-lg hover:bg-blue-200 transition duration-300">
                                            Mark as sold
                                        </button>
                                    </template>
                                    <template v-else>
                                       
                                    </template>
                                </div>
                            </div>
                            <a href="/products" class="absolute bottom-6 right-6 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                                View all
                            </a>
                        </div>
                    </div>

                    <!-- Marketplace Insights -->
                    <div class="bg-white overflow-hidden shadow-lg rounded-lg p-6">
                        <h2 class="text-2xl font-bold mb-6">Marketplace insights</h2>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span class="text-xl">Clicks on listings</span>
                                </div>
                                <span class="text-4xl font-bold">{{ totalClicks }}</span>
                            </div>
                            <div class="flex items-center justify-between bg-gray-100 p-4 rounded-lg">
                                <div class="flex items-center space-x-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                    </svg>
                                    <span class="text-xl">Listing saves</span>
                                </div>
                                <span class="text-4xl font-bold">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import axios from 'axios';

const props = defineProps({
    products: Array,
    totalClicks: Number,
});

const limit = ref(3);

const limitedProducts = computed(() => {
    return props.products.slice(0, limit.value);
});

const registerClick = async (productId) => {
    try {
        await axios.post(`/products/${productId}/register-click`);
    } catch (error) {
        console.error('Error registering click:', error);
    }
};

// Добавляем метод для покупки товара
const buyProduct = async (productId) => {
    try {
        await axios.post(`/products/${productId}/buy`);
        // Здесь можно добавить уведомление об успешной покупке
    } catch (error) {
        console.error('Error buying product:', error);
    }
};
</script>
