
<template>
  <div class="min-h-screen bg-gray-50">
    <div v-if="product" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Breadcrumb Navigation -->
      <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <Link :href="route('products.subcategories', { category: product.category })" class="hover:text-purple-600">
          {{ product.category }}
        </Link>
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span>{{ product.subcategory }}</span>
      </nav>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column - Photos -->
        <div class="space-y-4">
          <!-- Main Photo -->
          <div class="bg-white p-4 rounded-2xl shadow-sm">
            <div class="relative aspect-w-4 aspect-h-3 rounded-xl overflow-hidden">
              <img 
                :src="selectedPhoto || parsedPhotos[0]" 
                :alt="product.name"
                class="w-full h-full object-cover"
              />
            </div>
          </div>

          <!-- Thumbnail Grid -->
          <div v-if="parsedPhotos.length > 1" 
               class="grid grid-cols-4 sm:grid-cols-6 gap-4">
            <button
              v-for="(photo, index) in parsedPhotos"
              :key="index"
              @click="selectedPhoto = photo"
              class="relative aspect-square bg-white p-2 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-300"
              :class="{ 'ring-2 ring-purple-500': selectedPhoto === photo }"
            >
              <img 
                :src="photo" 
                :alt="`${product.name} - Photo ${index + 1}`"
                class="w-full h-full object-cover rounded-md"
              />
            </button>
          </div>
        </div>

        <!-- Right Column - Product Details -->
        <div class="bg-white rounded-2xl shadow-sm p-6 lg:p-8 space-y-6">
          <!-- Title and Price -->
          <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
            <p class="text-3xl font-bold text-purple-600">${{ formatPrice(product.price) }}</p>
          </div>

          <!-- Quick Info -->
          <div class="flex flex-wrap gap-4">
            <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full">
              <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ product.condition }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full">
              <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ product.location }}</span>
            </div>
            <div class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full">
              <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <span>{{ product.clicks || 0 }} views</span>
            </div>
          </div>

          <!-- Description -->
          <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-2">Description</h2>
            <p class="text-gray-600">{{ product.description }}</p>
          </div>

          <!-- Details Grid -->
          <div class="grid grid-cols-2 gap-6 py-6 border-t border-b border-gray-100">
            <div>
              <p class="text-sm text-gray-500 mb-1">Category</p>
              <p class="font-medium">{{ product.category }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 mb-1">Subcategory</p>
              <p class="font-medium">{{ product.subcategory }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 mb-1">Condition</p>
              <p class="font-medium">{{ product.condition }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500 mb-1">Location</p>
              <p class="font-medium">{{ product.location }}</p>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4">
            <button 
              @click="contactSeller(product.seller_id)"
              class="flex-1 bg-purple-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-300">
              Contact Seller
            </button>
            <button @click="addToCart(product)" class="flex-1 bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300">
              Add to Cart
            </button>
          </div>

          <!-- Posted Date -->
          <div class="text-sm text-gray-500">
            Posted {{ formatDate(product.created_at) }}
          </div>
        </div>
      </div>
    </div>

    <!-- Not Found State -->
    <div v-else class="max-w-md mx-auto text-center py-16">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Product Not Found</h3>
      <p class="mt-1 text-sm text-gray-500">We couldn't find the product you're looking for.</p>
      <div class="mt-6">
        
      </div>
    </div>
    <Toast v-if="toastMessage" :message="toastMessage" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
  product: Object,
});

const parsedPhotos = computed(() => {
  if (!props.product?.photos) return [];
  try {
    return JSON.parse(props.product.photos);
  } catch (e) {
    console.error('Error parsing photos:', e);
    return [];
  }
});

const selectedPhoto = ref(null);
const toastMessage = ref(null);

const addToCart = (product) => {
  router.post(route('cart.add'), {
    product_id: product.id,
    quantity: 1,
  }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: () => {
      toastMessage.value = 'Product added to cart!';
      setTimeout(() => {
        toastMessage.value = null;
      }, 3000);
    },
    onError: (errors) => {
      toastMessage.value = 'Error adding product to cart!';
      setTimeout(() => {
        toastMessage.value = null;
      }, 3000);
      console.error('Error adding product to cart:', errors);
    },
  });
};

const contactSeller = (sellerId) => {
  router.get(route('chat', { seller: sellerId }));
};

const formatPrice = (price) => {
  const numPrice = Number(price);
  return isNaN(numPrice) ? '0.00' : numPrice.toFixed(2);
};

const formatDate = (date) => {
  return new Intl.DateTimeFormat('en-US', {
    month: 'long',
    day: 'numeric',
    year: 'numeric'
  }).format(new Date(date));
};
</script>

<style scoped>
.aspect-w-4 {
  position: relative;
  padding-bottom: 75%;
}

.aspect-square {
  position: relative;
  padding-bottom: 100%;
}

.aspect-w-4 > img,
.aspect-square > img {
  position: absolute;
  height: 100%;
  width: 100%;
  top: 0;
  left: 0;
  object-fit: cover;
}
</style>