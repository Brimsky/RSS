<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16">
        <!-- Logo -->
        <div class="flex-shrink-0 flex items-center">
          <h1 class="text-2xl font-light text-gray-900">RSS</h1>
        </div>

        <!-- Navigation Links -->
        <div class="hidden md:block">
          <div class="ml-10 flex items-baseline space-x-4">
            <a href="#" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Home</a>
            <a href="#" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Products</a>
            <a href="#" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">About</a>
            <a href="#" class="text-gray-500 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium">Contact</a>
          </div>
        </div>

        <!-- Search Bar -->
        <div class="flex-grow mx-4 hidden md:block">
          <div class="relative">
            <input
              type="text"
              v-model="search"
              @keyup.enter="searchProducts"
              class="w-full bg-gray-100 rounded-full py-2 px-4 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:bg-white transition-all duration-300 text-sm"
              placeholder="Search products..."
            />
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 014 0z" />
              </svg>
            </div>
          </div>
        </div>

        <!-- Icons and User Info -->
        <div class="flex items-center space-x-4">
          <!-- Cart Icon -->
          <a href="/cart" class="text-gray-500 hover:text-gray-900 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </a>

          <!-- User Info / Account Dropdown -->
          <div class="relative">
            <button
              @click="isOpen = !isOpen"
              class="flex items-center space-x-2 text-gray-500 hover:text-gray-900 focus:outline-none"
            >
              <template v-if="$page.props.auth.user">
                <img :src="$page.props.auth.user.avatar" :alt="$page.props.auth.user.name" class="h-8 w-8 rounded-full">
                <span class="text-sm font-medium">{{ $page.props.auth.user.name }}</span>
              </template>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </button>

            <div
              v-if="isOpen"
              class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
            >
              <div class="py-1">
                <template v-if="$page.props.auth.user">
                  <Link :href="route('dashboard')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Dashboard
                  </Link>
                  <Link href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Profile
                  </Link>
                  <Link :href="route('logout')" method="post" as="button" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Logout
                  </Link>
                </template>
                <template v-else>
                  <Link :href="route('login')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Log in
                  </Link>
                  <Link v-if="canRegister" :href="route('register')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    Register
                  </Link>
                </template>
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
                  <div class="text-white" style="padding-left: 50px;"> <!-- added padding-left -->
                    <h2 class="text-3xl font-bold mb-2">{{ slide.title }}</h2>
                    <p class="mb-4">{{ slide.description }}</p>
                    <button class="bg-white text-gray-800 px-8 py-2 rounded-full hover:bg-gray-100 transition-all duration-300">
                      {{ slide.buttonText }}
                    </button>
                  </div>
                  <div class="w-1/2 h-full flex items-center justify-center">
                    <img :src="slide.image" :alt="slide.title" class="max-h-full object-contain scale-150" />
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
                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black/20 p-3 rounded-full hover:bg-black/50 hover:scale-110 transition-all duration-300 flex items-center justify-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-white">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
              </button>

              <button
                @click="nextSlide"
                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black/20 p-3 rounded-full hover:bg-black/50 hover:scale-110 transition-all duration-300 flex items-center justify-center"
              >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5 text-white">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Featured Products -->
          <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Featured Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div v-for="product in featuredProducts" :key="product.id" class="border rounded-lg p-4 hover:shadow-md transition-all duration-300">
                <img :src="product.image" :alt="product.name" class="w-full h-32 object-cover mb-2 rounded">
                <h3 class="font-medium text-gray-800">{{ product.name }}</h3>
                <p class="text-gray-600">{{ product.price }}</p>
                <button @click="addToCart(product)" class="mt-2 w-full bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600 transition-colors duration-300">
                  Add to Cart
                </button>
              </div>
            </div>
          </div>

          <!-- Top Sellers section -->
          <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Top Sellers</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div
                v-for="seller in topSellers"
                :key="seller.id"
                class="p-4 border rounded-lg text-center hover:shadow-md transition-all duration-300"
              >
                <img :src="seller.avatar" :alt="seller.name" class="w-16 h-16 mx-auto mb-2 rounded-full">
                <p class="font-medium text-gray-800">{{ seller.name }}</p>
                <p class="text-sm text-gray-600">{{ seller.rating }} ⭐</p>
              </div>
            </div>
          </div>

          <!-- Recent Reviews -->
          <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-6 text-gray-800">Recent Reviews</h2>
            <div class="space-y-4">
              <div v-for="review in recentReviews" :key="review.id" class="border-b pb-4 last:border-b-0">
                <div class="flex items-center mb-2">
                  <img :src="review.userAvatar" :alt="review.userName" class="w-10 h-10 rounded-full mr-3">
                  <div>
                    <p class="font-medium text-gray-800">{{ review.userName }}</p>
                    <p class="text-sm text-gray-600">{{ review.date }}</p>
                  </div>
                </div>
                <p class="text-gray-700">{{ review.comment }}</p>
                <div class="mt-2 flex items-center">
                  <span class="text-yellow-400">{{ '★'.repeat(review.rating) }}</span>
                  <span class="text-gray-300">{{ '★'.repeat(5 - review.rating) }}</span>
                </div>
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
import axios from 'axios';
import { Link } from '@inertiajs/vue3';


//photos for slides
import deliveryImage from '../icons/delivery.png';  
import summerSaleImage from '../icons/summer.png';
import newArrivalsImage from '../icons/box.png';
// Добавляем модель для поиска

const search = ref('');
const user = ref(null);

// Метод для отправки поискового запроса
const searchProducts = () => {
  if (search.value) {
    window.location.href = `/products?search=${search.value}`;
  }
};
defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
});



const isOpen = ref(false);
const currentSlide = ref(0);
const newsletterEmail = ref('');

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

const slides = [
  {
    title: "Free Delivery",
    description: "On orders over €80",
    buttonText: "Shop Now",
    bgColor: "#4A90E2",
    image: deliveryImage,
  },
  {
    title: "Summer Sale",
    description: "Up to 50% off",
    buttonText: "View Deals",
    bgColor: "#50E3C2",
    image: summerSaleImage,
  },
  {
    title: "New Arrivals",
    description: "Check out our latest products",
    buttonText: "Discover",
    bgColor: "#F5A623",
    image: newArrivalsImage,
  },
];

const featuredProducts = ref([]);
const topSellers = ref([]);
const recentReviews = ref([]);

const nextSlide = () => {
  currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
  currentSlide.value = (currentSlide.value - 1 + slides.length) % slides.length;
};
const addToCart = async (product) => {
  try {
    // This would typically be a POST request to your Laravel backend
    await axios.post('/api/cart/add', { productId: product.id });
    console.log(`Added ${product.name} to cart`);
    // You might want to update a cart count or show a success message here
  } catch (error) {
    console.error('Error adding to cart:', error);
    // Handle the error, maybe show an error message to the user
  }
};

const subscribeNewsletter = async () => {
  try {
    // This would typically be a POST request to your Laravel backend
    await axios.post('/api/newsletter/subscribe', { email: newsletterEmail.value });
    console.log(`Subscribed email: ${newsletterEmail.value}`);
    newsletterEmail.value = '';
    // You might want to show a success message here
  } catch (error) {
    console.error('Error subscribing to newsletter:', error);
    // Handle the error, maybe show an error message to the user
  }
};

let slideInterval;

onMounted(async () => {
  try {
    // Fetch featured products from Laravel backend
    const productsResponse = await axios.get('/api/featured-products');
    featuredProducts.value = productsResponse.data;

    // Fetch top sellers from Laravel backend
    const sellersResponse = await axios.get('/api/top-sellers');
    topSellers.value = sellersResponse.data;

    // Fetch recent reviews from Laravel backend
    const reviewsResponse = await axios.get('/api/recent-reviews');
    recentReviews.value = reviewsResponse.data;
  } catch (error) {
    console.error('Error fetching data:', error);
    // Handle the error, maybe show an error message to the user
  }

  slideInterval = setInterval(nextSlide, 5000);
});

onBeforeUnmount(() => {
  clearInterval(slideInterval);
});
</script>

<style scoped>
.bg-gradient-to-r {
  background: linear-gradient(to right, #4b5563, #1f2937);
}

.bg-gradient-to-l {
  background: linear-gradient(to left, #4b5563, #1f2937);
}

</style>