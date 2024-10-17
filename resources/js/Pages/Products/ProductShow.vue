<template>
  <AuthenticatedLayout>
    <div class="container mx-auto p-6">
      <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline">{{ error }}</span>
      </div>
      <div v-else-if="product" class="flex justify-between items-center">
        <h1 class="text-3xl font-bold">{{ product.name }}</h1>
        <a href="/products" class="btn btn-secondary">Back to Products</a>
      </div>
      <div v-else class="text-center py-8">
        <p>Loading product details...</p>
      </div>
      <div v-if="product" class="mt-6 flex gap-6">
        <img :src="product.photo" :alt="product.name" class="w-1/3 h-auto rounded-lg">
        <div class="flex-1">
          <p class="text-xl font-semibold">Price: ${{ parseFloat(product.price).toFixed(2) }}</p>
          <p class="mt-4">{{ product.description }}</p>
          <div class="mt-6 flex items-center space-x-4">
            <!-- Add to Cart Button -->
            <button @click="addToCart(product)" class="btn btn-primary" :disabled="form.processing">
              {{ form.processing ? 'Adding...' : 'Add to Cart' }}
            </button>
            <!-- Listing Saves Button -->
            <button class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-lg hover:bg-gray-300 transition duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const page = usePage();
const product = ref(page.props.product);
const error = ref(null);

const form = useForm({
  product_id: null,
});

const addToCart = (productToAdd) => {
  if (!productToAdd || !productToAdd.id) {
    console.error("Invalid product data:", productToAdd);
    error.value = 'Unable to add product to cart. Please try again.';
    return;
  }

  form.product_id = productToAdd.id;

  form.post('/cart/add', {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      alert('Product added to cart!');
    },
    onError: (errors) => {
      console.error("Failed to add product to cart:", errors);
      error.value = 'Failed to add product to cart. Please try again.';
    },
  });
};
</script>

<style scoped>
.container {
  max-width: 1200px;
}

h1 {
  display: flex;
  align-items: center;
}

.bg-red-100 {
  background-color: #fcebd3;
}

.text-red-700 {
  color: #c53030;
}

.rounded {
  border-radius: 0.25rem;
}

.btn-primary {
  background-color: #4299e1;
  color: white;
  padding: 0.75rem 1.5rem;
  border-radius: 0.25rem;
  display: inline-block;
}

.btn-secondary {
  background-color: #718096;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 0.25rem;
  display: inline-block;
}

.flex {
  display: flex;
  align-items: center;
}

.space-x-4 {
  gap: 1rem;
}
</style>
