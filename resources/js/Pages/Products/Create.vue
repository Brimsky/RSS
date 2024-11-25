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

        <h1 class="text-3xl font-bold text-center mb-12">Create New Listing</h1>

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
                <img :src="photoPreviewUrls[index]" alt="Product photo" class="w-full h-full object-cover" />
                <button 
                  @click="removePhoto(index)" 
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
                <input
                  type="file"
                  ref="fileInput"
                  @change="handlePhotoUpload"
                  accept="image/*"
                  multiple
                  class="hidden"
                />
              </div>
            </div>
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
                      min="0.01"
                      id="price"
                      class="w-full pl-7 pr-12 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                      :class="{ 'border-red-500': priceError }"
                      placeholder="0.00"
                      required
                      @input="validatePrice"
                    />
                    <div v-if="priceError" class="text-red-500 text-sm mt-1">
                      {{ priceError }}
                    </div>
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
                    <option value="clothing">Clothing</option>
                    <option value="furniture">Furniture</option>
                    <option value="books">Books</option>
                    <option value="automotive">Automotive</option>
                    <option value="sports">Sports</option>
                    <option value="home">Home</option>
                    <option value="tools">Tools</option>
                    <option value="toys">Toys</option>
                    <option value="beauty">Beauty</option>
                    <option value="health">Health</option>
                  </select>
                </div>
                <div>
                  <label for="subcategory" class="block text-sm font-medium text-gray-700 mb-1">Sub Category</label>
                  <div v-if="form.category">
                    <select
                      v-model="form.subcategory"
                      id="subcategory"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                      required
                    >
                      <option value="">Select a sub category</option>
                      <template v-for="(subcategories, categoryName) in subcategoryOptions" :key="categoryName">
                        <template v-if="form.category === categoryName">
                          <option v-for="subcategory in subcategories" :key="subcategory" :value="subcategory">
                            {{ subcategory }}
                          </option>
                        </template>
                      </template>
                      <option value="custom">Custom</option>
                    </select>
                    <input
                      v-if="form.subcategory === 'custom'"
                      v-model="form.customSubcategory"
                      type="text"
                      id="customSubcategory"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-2"
                      placeholder="Enter a custom subcategory"
                      required
                    />
                  </div>
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
                class="w-full px-8 py-3 border border-transparent text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200"
                :disabled="form.processing"
              >
                {{ form.processing ? 'Creating Listing...' : 'Create Listing' }}
              </button>
            </div>
          </div>
        </form>

        <!-- Success Notification -->
        <div v-if="productAdded" class="mt-8 bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">Listing created successfully!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const subcategoryOptions = {
  electronics: ['Console', 'Laptop', 'Smartphone', 'Camera', 'TV'],
  clothing: ['Shirt', 'Pants', 'Dress', 'Shoes'],
  furniture: ['Sofa', 'Bed', 'Table', 'Chair'],
  books: ['Fiction', 'Non-Fiction', 'Textbook'],
  automotive: ['Automotive'],
  sports: ['Sports Equipment', 'Sports Apparel'],
  home: ['Home Decor', 'Home Appliances'],
  tools: ['Power Tools', 'Hand Tools'],
  toys: ['Plush Toys', 'Board Games'],
  beauty: ['Makeup', 'Skincare'],
  health: ['Supplements', 'Medical Devices'],
};

const fileInput = ref(null);
const productAdded = ref(false);
const priceError = ref('');
const photoPreviewUrls = ref([]);

const form = useForm({
  name: '',
  price: '',
  description: '',
  category: '',
  subcategory: '',
  customSubcategory: '',
  condition: '',
  location: '',
  photos: []
});

const validatePrice = () => {
  const price = parseFloat(form.price);
  if (isNaN(price) || price <= 0) {
    priceError.value = 'Price must be greater than 0';
    return false;
  }
  priceError.value = '';
  return true;
};

const triggerFileInput = () => {
  fileInput.value.click();
};

const handlePhotoUpload = (event) => {
  const files = Array.from(event.target.files);
  
  // Validate file types and sizes
  const invalidFiles = files.filter(file => {
    const isValidType = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'].includes(file.type);
    const isValidSize = file.size <= 2 * 1024 * 1024; // 2MB limit
    return !isValidType || !isValidSize;
  });

  if (invalidFiles.length > 0) {
    alert('Please only upload images (JPG, PNG, GIF) under 2MB in size');
    return;
  }

  // Add files to form and create preview URLs
  files.forEach(file => {
    form.photos.push(file);
    photoPreviewUrls.value.push(URL.createObjectURL(file));
  });
};

const removePhoto = (index) => {
  form.photos.splice(index, 1);
  URL.revokeObjectURL(photoPreviewUrls.value[index]);
  photoPreviewUrls.value.splice(index, 1);
};

const submitForm = () => {
  if (!validatePrice()) {
    return;
  }

  if (!form.photos || form.photos.length === 0) {
    alert('Please add at least one photo');
    return;
  }

  // Create FormData and append all form fields
  const formData = new FormData();
  formData.append('name', form.name);
  formData.append('price', form.price);
  formData.append('description', form.description);
  formData.append('category', form.category);
  formData.append('subcategory', form.subcategory);
  formData.append('condition', form.condition);
  formData.append('location', form.location);
  
  // Append photos
  form.photos.forEach((photo, index) => {
    formData.append(`photos[${index}]`, photo);
  });

  // Post the form using Inertia with FormData
  form.post(route('products.store'), {
    forceFormData: true,
    onSuccess: () => {
      productAdded.value = true;
      setTimeout(() => {
        window.location.href = route('products.index');
      }, 2000);
    },
    onError: (errors) => {
      console.error(errors);
      if (errors.photos) {
        alert('Error uploading photos. Please try again.');
      }
    }
  });
};
</script>