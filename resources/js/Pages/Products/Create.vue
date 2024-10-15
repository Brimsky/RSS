<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-6xl mx-auto">
        <!-- Back button -->
        <div class="mb-8">
          <Link href="/products" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Products
          </Link>
        </div>
  
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">
          <div class="px-6 py-8 bg-black text-white">
            <h2 class="text-3xl font-bold">Create New Product</h2>
            <p class="mt-2 text-gray-300">Add your product details and photos below</p>
          </div>
  
          <div class="p-8">
            <div class="flex flex-col lg:flex-row lg:space-x-8">
              <!-- Left side: Photo upload -->
              <div class="w-full lg:w-1/3 mb-8 lg:mb-0">
                <div class="bg-gray-50 rounded-lg p-4 border-2 border-dashed border-gray-300 h-96">
                  <div v-if="form.photos.length === 0" class="flex flex-col items-center justify-center h-full">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="mt-4 text-sm text-gray-500">No photos added yet</p>
                  </div>
                  <div v-else class="grid grid-cols-2 gap-4 h-full overflow-y-auto">
                    <div v-for="(photo, index) in form.photos" :key="index" class="relative">
                      <img :src="photo" alt="Product preview" class="w-full h-40 object-cover rounded-md" />
                      <button @click="removePhoto(index)" class="absolute top-2 right-2 bg-black bg-opacity-50 text-white rounded-full p-1 hover:bg-opacity-70 transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <label for="photo-upload" class="mt-4 w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-black hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 cursor-pointer transition-colors duration-200">
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                  </svg>
                  Add Photos
                </label>
                <input
                  id="photo-upload"
                  type="file"
                  @change="handlePhotoUpload"
                  accept="image/*"
                  multiple
                  class="hidden"
                />
              </div>
  
              <!-- Right side: Product details form -->
              <div class="w-full lg:w-2/3">
                <form @submit.prevent="submitForm" class="space-y-6">
                  <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                    <input
                      v-model="form.name"
                      type="text"
                      id="name"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm transition-shadow duration-200"
                      required
                    />
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
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select
                      v-model="form.category"
                      id="category"
                      class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-black focus:border-black sm:text-sm rounded-md transition-shadow duration-200"
                    >
                      <option value="">Select a category</option>
                      <option value="electronics">Electronics</option>
                      <option value="clothing">Clothing</option>
                      <option value="home">Home & Garden</option>
                      <option value="sports">Sports & Outdoors</option>
                      <option value="toys">Toys & Games</option>
                    </select>
                  </div>
  
                  <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea
                      v-model="form.description"
                      id="description"
                      rows="6"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-black focus:border-black sm:text-sm transition-shadow duration-200"
                      required
                    ></textarea>
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
                      {{ form.processing ? 'Creating...' : 'Create Product' }}
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Success Notification -->
        <div v-if="productAdded" class="mt-8 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">Product added successfully!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { useForm, Link } from '@inertiajs/vue3';
  
  const form = useForm({
    name: '',
    price: '',
    description: '',
    category: '',
    photos: []
  });
  
  const productAdded = ref(false);
  
  const handlePhotoUpload = (event) => {
    const files = event.target.files;
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      const reader = new FileReader();
      reader.onload = (e) => {
        form.photos.push(e.target.result);
      };
      reader.readAsDataURL(file);
    }
  };
  
  const removePhoto = (index) => {
    form.photos.splice(index, 1);
  };
  
  const submitForm = async () => {
    await form.post('/products', {
      onSuccess: () => {
        productAdded.value = true;
        setTimeout(() => {
          productAdded.value = false;
        }, 5000); // Hide the success message after 5 seconds
      },
      onError: () => {
        productAdded.value = false;
      }
    });
  };
  </script>