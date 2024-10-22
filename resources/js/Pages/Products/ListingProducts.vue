```vue
<template>
  <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <!-- Header Section -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent">
        {{ category }}
      </h1>
      <div class="flex items-center text-gray-600 mt-2">
        <span>{{ subcategory }}</span>
        <span class="mx-2">•</span>
        <span>{{ products.length }} items</span>
      </div>
    </div>
    
    <!-- No Products Message -->
    <div v-if="products.length === 0" class="bg-white rounded-xl shadow-sm p-8">
      <div class="text-center">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <p class="mt-4 text-lg font-medium text-gray-900">No products found</p>
        <p class="mt-2 text-gray-500">We couldn't find any products in this category and subcategory.</p>
      </div>
    </div>
    
    <!-- Products List -->
    <div v-else class="space-y-4">
      <Link
        v-for="product in products" 
        :key="product.id"
        :href="route('products.show', product.id)"
        @click="registerClick(product.id)"
        class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
      >
        <div class="flex items-center p-4 sm:p-6">
          <!-- Product Image -->
          <div class="flex-shrink-0">
            <img 
              :src="product.image || '/images/placeholder.png'" 
              :alt="product.name"
              class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-xl"
            />
          </div>

          <!-- Product Info -->
          <div class="flex-1 min-w-0 px-4 sm:px-6">
            <div class="flex items-center justify-between">
              <h2 class="text-lg font-semibold text-gray-900 truncate">
                {{ product.name }}
              </h2>
              <div class="flex items-center">
                <!-- Price -->
                <p class="text-lg font-bold text-purple-600">
                  ${{ formatPrice(product.price) }}
                </p>
                <!-- Arrow Icon -->
                <svg class="w-5 h-5 ml-4 text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all duration-300" 
                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </div>
            </div>

            <!-- Product Description -->
            <p class="mt-1 text-sm text-gray-500 line-clamp-2">
              {{ product.description }}
            </p>

            <!-- Product Details -->
            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Listed {{ formatDate(product.created_at) }}</span>
              </div>
              
              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span>{{ product.location || 'Location not specified' }}</span>
              </div>

              <div class="flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>{{ product.views || 0 }} views</span>
              </div>
            </div>
          </div>
        </div>
      </Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  category: String,
  subcategory: String,
  products: Array,
});

const registerClick = async (productId) => {
    try {
        await axios.post(`/products/${productId}/register-click`);
    } catch (error) {
        console.error('Error registering click:', error);
    }
};

const formatPrice = (price) => {
  const numPrice = Number(price);
  return isNaN(numPrice) ? '0.00' : numPrice.toFixed(2);
};

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
};
</script>

<style scoped>
.bg-clip-text {
  -webkit-background-clip: text;
  background-clip: text;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>
