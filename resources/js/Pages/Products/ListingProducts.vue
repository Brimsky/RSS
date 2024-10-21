<template>
  <div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">{{ category }} - {{ subcategory }}</h1>
    
    <div v-if="products.length === 0" class="text-center py-8">
      <p class="text-red-500">No products found in this category and subcategory.</p>
    </div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="product in products" :key="product.id" 
           class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition-shadow duration-300">
        <h2 class="text-xl font-semibold mb-2">{{ product.name }}</h2>
        <p class="text-gray-600 mb-2">{{ product.description }}</p>
        <p class="text-lg font-bold mb-2">${{ formatPrice(product.price) }}</p>
        <Link :href="route('products.show', product.id)" 
            class="mt-2 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors duration-300">
            View Details
        </Link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  category: String,
  subcategory: String,
  products: Array,
});

const formatPrice = (price) => {
  const numPrice = Number(price);
  return isNaN(numPrice) ? '0.00' : numPrice.toFixed(2);
};
</script>