<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
          <div class="px-6 py-8 bg-black text-white">
            <h1 class="text-3xl font-bold text-center">Edit Product</h1>
          </div>
  
          <div class="p-8">
            <form @submit.prevent="submitForm" class="space-y-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  v-model="form.name"
                  type="text"
                  id="name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm transition-shadow duration-200"
                  placeholder="Enter product name"
                  required
                />
              </div>
  
              <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                  v-model="form.description"
                  id="description"
                  rows="4"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm transition-shadow duration-200"
                  placeholder="Enter product description"
                  required
                ></textarea>
              </div>
  
              <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Price (€)</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">€</span>
                  </div>
                  <input
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    id="price"
                    class="block w-full pl-7 pr-12 border-gray-300 rounded-md focus:ring-black focus:border-black sm:text-sm transition-shadow duration-200"
                    placeholder="0.00"
                    required
                  />
                </div>
              </div>
  
              <div>
                <button
                  type="submit"
                  class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200"
                  :disabled="form.processing"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ form.processing ? 'Updating...' : 'Update Product' }}
                </button>
              </div>
            </form>
  
            <!-- Success notification -->
            <div v-if="productUpdated" class="mt-8 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm font-medium text-green-800">Product updated successfully!</p>
                </div>
              </div>
            </div>
  
            <!-- Back to Products button -->
            <div class="mt-8 text-center">
              <Link
                href="/products"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition-colors duration-200"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Products
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, Link } from '@inertiajs/vue3';
  
  const props = defineProps({
    product: Object
  });
  
  const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
  });
  
  const productUpdated = ref(false);
  
  const submitForm = async () => {
    await form.put(`/products/${props.product.id}`, {
      onSuccess: () => {
        productUpdated.value = true;
        setTimeout(() => {
          productUpdated.value = false;
        }, 5000); // Hide the success message after 5 seconds
      },
      onError: () => {
        productUpdated.value = false;
      }
    });
  };
  </script>