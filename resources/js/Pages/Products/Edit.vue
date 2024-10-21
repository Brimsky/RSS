<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto">
        
        <!-- Back button -->
        <div class="mb-8">
          <Link :href="route('products.index')" class="inline-flex items-center text-gray-600 hover:text-gray-900 transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Products
          </Link>
        </div>

        <h1 class="text-3xl font-bold text-center mb-12">Edit Listing</h1>

        <form @submit.prevent="submitForm" class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Left Column: Photos Section -->
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold mb-4">Photos</h2>
            <div class="grid grid-cols-2 gap-4">
              <div 
                v-for="(photo, index) in form.photos" 
                :key="index" 
                class="relative aspect-square bg-gray-100 rounded-lg overflow-hidden"
              >
                <img :src="photo" alt="Product photo" class="w-full h-full object-cover" />
                <button 
                  @click.prevent="removePhoto(index)" 
                  class="absolute top-2 right-2 bg-white rounded-full p-1 shadow-md hover:bg-gray-100 transition-colors duration-200"
                >
                  <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
              <div 
                @click="triggerFileInput"
                class="aspect-square bg-gray-100 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-200 transition-colors duration-200"
              >
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
              </div>
            </div>
            <input
              ref="fileInput"
              type="file"
              @change="handlePhotoUpload"
              accept="image/*"
              multiple
              class="hidden"
            />
          </div>

          <!-- Right Column: Listing Details -->
          <div class="space-y-6">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h2 class="text-xl font-semibold mb-4">Listing Details</h2>
              <div class="space-y-4">
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                  <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., Xbox 360 Console"
                    required
                  />
                </div>
                <div>
                  <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                  <div class="relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                      <span class="text-gray-500 sm:text-sm">€</span>
                    </div>
                    <input
                      v-model="form.price"
                      type="number"
                      step="0.01"
                      id="price"
                      class="w-full pl-7 pr-12 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      placeholder="0.00"
                      required
                    />
                  </div>
                </div>
                <div>
                  <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                  <select
                    v-model="form.category"
                    id="category"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                  >
                    <option value="">Select a category</option>
                    <option value="electronics">Electronics</option>
                    <option value="furniture">Furniture</option>
                    <option value="clothing">Clothing</option>
                    <!-- Add more categories as needed -->
                  </select>
                </div>
                <div>
                  <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
                  <select
                    v-model="form.subcategory"
                    id="subcategory"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                  >
                    <option value="">Select a sub category</option>
                    <option value="console">Console</option>
                    <option value="laptop">Laptop</option>
                    <option value="smartphone">Smartphone</option>
                    <!-- Add more subcategories as needed -->
                  </select>
                </div>
                <div>
                  <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                  <select
                    v-model="form.condition"
                    id="condition"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required
                  >
                    <option value="">Select condition</option>
                    <option value="new">New</option>
                    <option value="like-new">Like New</option>
                    <option value="good">Good</option>
                    <option value="fair">Fair</option>
                    <option value="poor">Poor</option>
                  </select>
                </div>
                <div>
                  <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                  <input
                    v-model="form.location"
                    type="text"
                    id="location"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="e.g., Riga"
                    required
                  />
                </div>
              </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
              <h2 class="text-xl font-semibold mb-4">Description</h2>
              <textarea
                v-model="form.description"
                id="description"
                rows="6"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Describe your item in detail..."
                required
              ></textarea>
            </div>

            <!-- Submit button -->
            <div class="flex justify-end">
              <button
                type="submit"
                class="px-8 py-3 border border-transparent text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Updating...' : 'Update Listing' }}
              </button>
            </div>
          </div>
        </form>

        <!-- Success Notification -->
        <div v-if="productUpdated" class="mt-8 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">Listing updated successfully!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="form.errors.update_error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
    <strong class="font-bold">Error!</strong>
    <span class="block sm:inline">{{ form.errors.update_error }}</span>
  </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
  product: Object,
});

const productLoaded = ref(false);
const productUpdated = ref(false);
const fileInput = ref(null);

const form = useForm({
  name: '',
  description: '',
  price: '',
  category: '',
  subcategory: '',
  location: '',
  condition: '',
  photos: undefined, // Use undefined as the default value
});

watch(() => props.product, (newProduct) => {
  if (newProduct) {
    form.name = newProduct.name || '';
    form.description = newProduct.description || '';
    form.price = newProduct.price || '';
    form.category = newProduct.category || '';
    form.subcategory = newProduct.subcategory || '';
    form.location = newProduct.location || '';
    form.condition = newProduct.condition || '';
    
    // Only set photos if they exist and are not an empty array
    if (newProduct.photos && Array.isArray(newProduct.photos) && newProduct.photos.length > 0) {
      form.photos = newProduct.photos;
    } else {
      form.photos = undefined;
    }
    
    productLoaded.value = true;
  }
}, { immediate: true });

onMounted(() => {
  console.log('Product data:', props.product);
});

const submitForm = () => {
  if (!props.product) {
    console.error('Cannot submit form: Product data is not available');
    return;
  }
  
  // Create a new object with only the fields that have changed
  const formData = Object.keys(form).reduce((acc, key) => {
    if (form[key] !== props.product[key]) {
      acc[key] = form[key];
    }
    return acc;
  }, {});

  // If photos is undefined or an empty array, explicitly set it to null
  if (!formData.photos || (Array.isArray(formData.photos) && formData.photos.length === 0)) {
    formData.photos = null;
  }

  console.log('Submitting form data:', formData);  // Log the data being sent

  form.put(route('products.update', props.product.id), formData, {
    preserveScroll: true,
    onSuccess: (page) => {
      productUpdated.value = true;
      console.log('Product updated successfully', page);
      // Redirect to products index page
      window.location.href = route('products.index');
    },
    onError: (errors) => {
      productUpdated.value = false;
      console.error('Form submission errors:', errors);
    },
  });
};
</script>