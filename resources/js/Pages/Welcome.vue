<template>
    <div class="min-h-screen bg-gray-50">
      <!-- Navbar -->
      <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-2xl font-bold text-gray-900">RSS</h1>
            </div>
  
            <!-- Search Bar -->
            <div class="flex-grow mx-4">
              <div class="relative">
                <input
                  type="text"
                  class="w-full bg-gray-100 rounded-full py-2 px-4 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all duration-300"
                  placeholder="Search"
                />
                <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
              </div>
            </div>
  
            <!-- Icons -->
            <div class="flex items-center space-x-4">
              <!-- Cart Icon -->
              <button class="text-gray-600 hover:text-gray-900 focus:outline-none">
                <a href="/cart">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                </a>
              </button>
  
              <!-- User Icon / Account Dropdown -->
              <div class="relative">
                <button
                  @click="isOpen = !isOpen"
                  class="text-gray-600 hover:text-gray-900 focus:outline-none"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </button>
  
                <div
                  v-if="isOpen"
                  class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                >
                  <div class="py-1">
                    <a
                      href="/login"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                      </svg>
                      Login
                    </a>
                    <a
                      href="/register"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                      </svg>
                      Register
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </nav>
  
      <!-- Main Content -->
      <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <!-- Categories -->
          <div class="col-span-1">
            <div class="bg-white shadow rounded-lg p-4">
              <h2 class="text-lg font-semibold mb-4 text-gray-800">Categories</h2>
              <ul class="space-y-2">
                <li
                  v-for="category in categories"
                  :key="category"
                  class="p-2 hover:bg-gray-100 rounded cursor-pointer transition-all duration-300"
                >
                  {{ category }}
                </li>
              </ul>
            </div>
          </div>
  
          <!-- Main content area -->
          <div class="col-span-1 md:col-span-3 space-y-6">
            <!-- Interactive Banner -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
              <div class="relative h-64">
                <template v-for="(slide, index) in slides" :key="index">
                  <transition
                    enter-active-class="transition-opacity duration-500"
                    leave-active-class="transition-opacity duration-500"
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                  >
                    <div
                      v-if="currentSlide === index"
                      class="absolute inset-0 flex items-center justify-between p-8"
                      :style="{ backgroundColor: slide.bgColor }"
                    >
                      <div class="text-white">
                        <h2 class="text-3xl font-bold mb-2">{{ slide.title }}</h2>
                        <p class="mb-4">{{ slide.description }}</p>
                        <button class="bg-white text-gray-800 px-6 py-2 rounded-full hover:bg-gray-100 transition-all duration-300">
                          {{ slide.buttonText }}
                        </button>
                      </div>
                      <div class="w-1/2 h-full flex items-center justify-center">
                        <img :src="slide.image" :alt="slide.title" class="max-h-full object-contain" />
                      </div>
                    </div>
                  </transition>
                </template>
  
                <!-- Navigation dots -->
                <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                  <button
                    v-for="(_, index) in slides"
                    :key="index"
                    @click="currentSlide = index"
                    class="w-3 h-3 rounded-full transition-all duration-300"
                    :class="currentSlide === index ? 'bg-white' : 'bg-white/50'"
                  ></button>
                </div>
  
                <!-- Arrow buttons -->
                <button
                  @click="prevSlide"
                  class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white/30 p-2 rounded-full hover:bg-white/50 transition-all duration-300"
                >
                  ◀
                </button>
                <button
                  @click="nextSlide"
                  class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white/30 p-2 rounded-full hover:bg-white/50 transition-all duration-300"
                >
                  ▶
                </button>
              </div>
            </div>
  
            <!-- Top Sellers section -->
            <div class="bg-white shadow rounded-lg p-6">
              <h2 class="text-xl font-semibold mb-6 text-gray-800">Top Sellers</h2>
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                  v-for="seller in topSellers"
                  :key="seller"
                  class="p-4 border rounded-lg text-center hover:shadow-md transition-all duration-300"
                >
                  <div class="w-16 h-16 mx-auto mb-2 bg-gray-200 rounded-full"></div>
                  <p class="font-medium text-gray-800">{{ seller }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from "vue";
  
  const isOpen = ref(false);
  const currentSlide = ref(0);
  
  const categories = [
    "Electronics",
    "Fashion",
    "Home & Garden",
    "Health & Beauty",
    "Sports & Outdoors",
    "Real Estate",
    "Art & Collectibles",
    "Office Supplies",
  ];
  
  const topSellers = ["Spectra", "Mega Knight", "Baseus", "DJI"];
  
  const slides = [
    {
      title: "Free Delivery",
      description: "On orders over €80",
      buttonText: "Shop Now",
      bgColor: "#4A90E2",
      image: "/api/placeholder/300/200",
    },
    {
      title: "Summer Sale",
      description: "Up to 50% off",
      buttonText: "View Deals",
      bgColor: "#50E3C2",
      image: "/api/placeholder/300/200",
    },
    {
      title: "New Arrivals",
      description: "Check out our latest products",
      buttonText: "Discover",
      bgColor: "#F5A623",
      image: "/api/placeholder/300/200",
    },
  ];
  
  const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
  };
  
  const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
  };
  
  let slideInterval;
  
  onMounted(() => {
    slideInterval = setInterval(nextSlide, 5000);
  });
  
  onBeforeUnmount(() => {
    clearInterval(slideInterval);
  });
  </script>