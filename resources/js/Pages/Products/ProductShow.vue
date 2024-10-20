<template>
  <Layout>
    <div class="container mx-auto p-6">
      <h1 class="text-3xl font-bold mb-6">{{ category }} - Subcategories</h1>
      
      <div v-if="subcategories.length === 0" class="text-center py-8">
        <p class="text-red-500">{{ message || 'No subcategories found for this category.' }}</p>
      </div>
      
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="subcategory in subcategories" :key="subcategory" 
             class="bg-white shadow rounded-lg p-4 hover:shadow-lg transition-shadow duration-300">
          <h2 class="text-xl font-semibold mb-2">{{ subcategory }}</h2>
          <Link :href="route('products.listings', { category, subcategory })" 
                class="mt-2 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition-colors duration-300">
            View Listings
          </Link>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';

defineProps({
  category: String,
  subcategories: Array,
  message: String
});
</script>