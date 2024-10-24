<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Logo -->
                    <a href="/" class="group">
                        <h1
                            class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent group-hover:from-purple-500 group-hover:to-blue-400 transition-all duration-300"
                        >
                            RSS
                        </h1>
                    </a>

                    <!-- Mobile Menu Button -->
                    <div class="flex items-center md:hidden">
                        <button
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="text-gray-600 hover:text-purple-600 focus:outline-none"
                        >
                            <svg
                                v-if="!isMobileMenuOpen"
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                            <svg
                                v-else
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>

                    <!-- Desktop Navigation -->

                    <!-- Search Bar - Desktop -->
                    <div class="hidden md:block flex-grow mx-4 max-w-md">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="search"
                                @keyup.enter="searchProducts"
                                class="w-full bg-gray-100 rounded-full py-2 px-4 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-purple-200 focus:bg-white transition-all duration-300 text-sm"
                                placeholder="Search products..."
                            />
                            <div
                                class="absolute left-3 top-1/2 transform -translate-y-1/2"
                            >
                                <svg
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 014 0z"
                                    />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center space-x-4">
                        <a
                            href="/cart"
                            class="text-gray-600 hover:text-purple-600 transition-colors duration-300"
                        >
                            <div class="relative">
                                <svg
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                <span
                                    v-if="cartCount > 0"
                                    class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                                >
                                    {{ cartCount }}
                                </span>
                            </div>
                        </a>

                        <!-- User Menu -->
                        <div class="relative" v-click-outside="closeUserMenu">
                            <button
                                @click="toggleUserMenu"
                                class="flex items-center space-x-2 text-gray-600 hover:text-purple-600 focus:outline-none transition-colors duration-300"
                            >
                                <template v-if="user">
                                    <div
                                        class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center"
                                    >
                                        <img
                                            v-if="avatarUrl"
                                            :src="avatarUrl"
                                            :alt="user.name"
                                            class="h-8 w-8 rounded-full object-cover"
                                        />
                                        <span
                                            v-else
                                            class="text-purple-600 font-medium"
                                        >
                                            {{
                                                user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}
                                        </span>
                                    </div>
                                    <span
                                        class="text-sm font-medium hidden md:block"
                                        >{{ user.name }}</span
                                    >
                                </template>
                                <svg
                                    v-else
                                    class="h-6 w-6"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </button>

                            <!-- User Dropdown -->
                            <div
                                v-if="isUserMenuOpen"
                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5"
                            >
                                <div class="py-1">
                                    <template v-if="user">
                                        <Link
                                            :href="route('dashboard')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50"
                                        >
                                            Dashboard
                                        </Link>
                                        <Link
                                            :href="route('profile.edit')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50"
                                        >
                                            Profile
                                        </Link>
                                        <Link
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-purple-50"
                                        >
                                            Logout
                                        </Link>
                                    </template>
                                    <template v-else>
                                        <Link
                                            :href="route('login')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50"
                                        >
                                            Log in
                                        </Link>
                                        <Link
                                            v-if="canRegister"
                                            :href="route('register')"
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50"
                                        >
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

        <!-- Mobile Menu -->
        <div
            v-if="isMobileMenuOpen"
            class="md:hidden bg-white border-b border-gray-200"
        >
            <div class="px-4 py-3 space-y-3">
                <!-- Mobile Search -->
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        @keyup.enter="searchProducts"
                        class="w-full bg-gray-100 rounded-full py-2 px-4 pl-10 pr-4 focus:outline-none focus:ring-2 focus:ring-purple-200 focus:bg-white transition-all duration-300 text-sm"
                        placeholder="Search products..."
                    />
                    <div
                        class="absolute left-3 top-1/2 transform -translate-y-1/2"
                    >
                        <svg
                            class="h-5 w-5 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 014 0z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <nav class="space-y-2">
                    <a
                        href="#"
                        class="block px-3 py-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-300"
                    >
                        Home
                    </a>
                    <a
                        href="#"
                        class="block px-3 py-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-300"
                    >
                        Products
                    </a>
                    <a
                        href="#"
                        class="block px-3 py-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-300"
                    >
                        About
                    </a>
                    <a
                        href="#"
                        class="block px-3 py-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors duration-300"
                    >
                        Contact
                    </a>
                </nav>
            </div>
        </div>

        <!-- Hero Banner/Slider -->
        <div class="relative bg-white overflow-hidden">
            <div class="relative h-[500px]">
                <template v-for="(slide, index) in slides" :key="index">
                    <transition
                        enter-active-class="transition-opacity duration-700"
                        leave-active-class="transition-opacity duration-700"
                        enter-from-class="opacity-0"
                        leave-to-class="opacity-0"
                    >
                        <div
                            v-if="currentSlide === index"
                            class="absolute inset-0 flex items-center"
                            :style="{ backgroundColor: slide.bgColor }"
                        >
                            <div
                                class="container mx-auto px-6 lg:px-8 flex items-center"
                            >
                                <div
                                    class="w-full lg:w-1/2 text-white space-y-6 z-10"
                                >
                                    <h1
                                        class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight"
                                    >
                                        {{ slide.title }}
                                    </h1>
                                    <p class="text-lg md:text-xl opacity-90">
                                        {{ slide.description }}
                                    </p>
                                    <button
                                        class="px-8 py-3 bg-white text-gray-900 rounded-full hover:bg-gray-100 transform hover:scale-105 transition-all duration-300"
                                    >
                                        {{ slide.buttonText }}
                                    </button>
                                </div>
                                <div class="hidden lg:block w-1/2 pl-16">
                                    <img
                                        :src="slide.image"
                                        :alt="slide.title"
                                        class="w-full h-auto object-contain transform hover:scale-105 transition-transform duration-500"
                                    />
                                </div>
                            </div>

                            <!-- Decorative Elements -->
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-blue-600/20"
                            ></div>
                            <div
                                class="absolute -bottom-32 -left-32 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-2xl opacity-20"
                            ></div>
                            <div
                                class="absolute -top-32 -right-32 w-96 h-96 bg-blue-600 rounded-full mix-blend-multiply filter blur-2xl opacity-20"
                            ></div>
                        </div>
                    </transition>
                </template>

                <!-- Slider Controls -->
                <div
                    class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-3"
                >
                    <button
                        v-for="(_, index) in slides"
                        :key="index"
                        @click="currentSlide = index"
                        class="w-3 h-3 rounded-full transition-all duration-300"
                        :class="
                            currentSlide === index
                                ? 'bg-white scale-125'
                                : 'bg-white/50 hover:bg-white/75'
                        "
                    ></button>
                </div>

                <!-- Arrow Navigation -->
                <button
                    @click="prevSlide"
                    class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-white/20 transition-all duration-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </button>

                <button
                    @click="nextSlide"
                    class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/10 backdrop-blur-sm p-3 rounded-full hover:bg-white/20 transition-all duration-300"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 5l7 7-7 7"
                        />
                    </svg>
                </button>
            </div>
        </div>

        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Categories Header -->
                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="category in categories"
                        :key="category.id"
                        class="group bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer"
                        @click="
                            $inertia.visit(
                                route('products.subcategories', category.id),
                            )
                        "
                    >
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                <!-- Category Icon -->
                                <div
                                    class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-100 to-blue-100 group-hover:from-purple-200 group-hover:to-blue-200 flex items-center justify-center transition-colors duration-300"
                                >
                                    <component
                                        :is="category.icon"
                                        class="w-7 h-7 text-purple-600 group-hover:text-purple-700 transition-colors duration-300"
                                    />
                                </div>

                                <!-- Category Info -->
                                <div class="flex-1">
                                    <h3
                                        class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-300"
                                    >
                                        {{ category.name }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Action Indicator -->
                            <div
                                class="mt-4 flex items-center text-sm text-purple-600 font-medium"
                            >
                                <span>Browse Collection</span>
                                <svg
                                    class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-300"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 5l7 7-7 7"
                                    />
                                </svg>
                            </div>
                        </div>

                        <!-- Bottom Gradient Bar -->
                        <div
                            class="h-1.5 bg-gradient-to-r"
                            :class="category.bgColor"
                        ></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Us Section -->
        <div class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="lg:grid lg:grid-cols-2 lg:gap-16 items-center">
                    <div class="mb-12 lg:mb-0">
                        <h2
                            class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent mb-6"
                        >
                            Why Choose Our Marketplace?
                        </h2>

                        <div class="space-y-8">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-purple-100 flex items-center justify-center"
                                    >
                                        <!-- Continuing from the About Us section -->
                                        <svg
                                            class="w-6 h-6 text-purple-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="text-xl font-semibold text-gray-900 mb-2"
                                    >
                                        Secure Transactions
                                    </h3>
                                    <p class="text-gray-600">
                                        Enhanced security protocols and buyer
                                        protection ensure your transactions are
                                        always safe.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-6 h-6 text-blue-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="text-xl font-semibold text-gray-900 mb-2"
                                    >
                                        Lightning Fast Delivery
                                    </h3>
                                    <p class="text-gray-600">
                                        Quick processing and efficient logistics
                                        ensure your products reach you in record
                                        time.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div
                                        class="w-12 h-12 rounded-lg bg-green-100 flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-6 h-6 text-green-600"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h3
                                        class="text-xl font-semibold text-gray-900 mb-2"
                                    >
                                        Best Prices Guaranteed
                                    </h3>
                                    <p class="text-gray-600">
                                        Competitive pricing and regular deals
                                        ensure you get the best value for your
                                        money.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="aspect-w-16 aspect-h-9 rounded-2xl overflow-hidden shadow-xl"
                        >
                            <img
                                src="../icons/GSlogo.jpg"
                                alt="Marketplace Preview"
                                class="object-cover"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-2xl"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Sellers Section -->
        <div class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2
                        class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent"
                    >
                        Top Sellers
                    </h2>
                    <p class="mt-4 text-gray-600">
                        Meet our most successful marketplace vendors
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6"
                >
                    <div
                        v-for="seller in topSellers"
                        :key="seller.id"
                        class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 p-6"
                    >
                        <div class="text-center">
                            <div class="relative w-20 h-20 mx-auto mb-4">
                                <img
                                    :src="seller.avatar"
                                    :alt="seller.name"
                                    class="w-full h-full rounded-full object-cover"
                                />
                                <div
                                    class="absolute bottom-0 right-0 bg-green-500 w-4 h-4 rounded-full border-2 border-white"
                                ></div>
                            </div>

                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ seller.name }}
                            </h3>
                            <p class="text-sm text-gray-500 mb-3">
                                {{ seller.category }}
                            </p>

                            <div
                                class="flex items-center justify-center text-yellow-400 mb-2"
                            >
                                <template v-for="n in 5" :key="n">
                                    <svg
                                        class="w-5 h-5"
                                        :class="{
                                            'text-yellow-400':
                                                n <= seller.rating,
                                            'text-gray-300': n > seller.rating,
                                        }"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        />
                                    </svg>
                                </template>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">
                                {{ seller.sales }}+ sales
                            </p>

                            <button
                                class="w-full px-4 py-2 text-sm font-medium text-purple-600 border border-purple-600 rounded-full hover:bg-purple-600 hover:text-white transition-colors duration-300"
                            >
                                View Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <Footer />
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import axios from "axios";

import deliveryImage from "../icons/delivery.png";
import summerSaleImage from "../icons/summer.png";
import newArrivalsImage from "../icons/box.png";

import Footer from "../Components/Footer.vue";

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
};

// Click Outside Directive
const vClickOutside = {
    mounted(el, binding) {
        el._clickOutside = (event) => {
            if (!(el === event.target || el.contains(event.target))) {
                binding.value(event);
            }
        };
        document.body.addEventListener("click", el._clickOutside);
    },
    unmounted(el) {
        document.body.removeEventListener("click", el._clickOutside);
    },
};

const page = usePage();
const user = ref(page.props.auth.user);
const avatarUrl = ref(null);
const isUserMenuOpen = ref(false);
const isMobileMenuOpen = ref(false);
const search = ref("");
const newsletterEmail = ref("");
const currentSlide = ref(0);
const cartCount = ref(0);
const isOpen = ref(false);
const categories = ref([]);
const isLoading = ref(true);
const error = ref(null);
const slideInterval = ref(null);

// Props definition
const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// Slides data with proper image paths
const slides = [
    {
        title: "Free Delivery on Your First Order",
        description: "Get free shipping on all orders over €80",
        buttonText: "Shop Now",
        bgColor: "#4A90E2",
        image: deliveryImage,
    },
    {
        title: "Summer Sale is Live",
        description: "Save up to 50% on selected items",
        buttonText: "View Deals",
        bgColor: "#50E3C2",
        image: summerSaleImage,
    },
    {
        title: "New Arrivals Daily",
        description: "Check out our latest collection",
        buttonText: "Discover More",
        bgColor: "#F5A623",
        image: newArrivalsImage,
    },
];

// Top sellers with placeholder images
const topSellers = ref([
    {
        id: 1,
        name: "John Smith",
        category: "Electronics",
        rating: 4.9,
        sales: "1.2k",
        avatar: "https://ui-avatars.com/api/?name=John+Smith&background=random",
    },
    {
        id: 2,
        name: "Sarah Johnson",
        category: "Fashion",
        rating: 4.8,
        sales: "950",
        avatar: "https://ui-avatars.com/api/?name=Sarah+Johnson&background=random",
    },
    {
        id: 3,
        name: "Michael Brown",
        category: "Home & Garden",
        rating: 4.7,
        sales: "820",
        avatar: "https://ui-avatars.com/api/?name=Michael+Brown&background=random",
    },
    {
        id: 4,
        name: "Emma Wilson",
        category: "Beauty",
        rating: 4.9,
        sales: "1.5k",
        avatar: "https://ui-avatars.com/api/?name=Emma+Wilson&background=random",
    },
    {
        id: 5,
        name: "David Lee",
        category: "Sports",
        rating: 4.6,
        sales: "760",
        avatar: "https://ui-avatars.com/api/?name=David+Lee&background=random",
    },
]);

// Computed properties
const maxProducts = computed(() => {
    if (!categories.value.length) return 0;
    return Math.max(...categories.value.map((cat) => cat.products_count || 0));
});

// Methods
const getAvatarUrl = () => {
    if (user.value?.media?.length > 0) {
        return (
            "/storage/" +
            user.value.media[0].id +
            "/" +
            user.value.media[0].file_name
        );
    }
    return null;
};

const toggleUserMenu = () => {
    isUserMenuOpen.value = !isUserMenuOpen.value;
};

const closeUserMenu = () => {
    isUserMenuOpen.value = false;
};

const searchProducts = () => {
    if (search.value) {
        window.location.href = `/products?search=${encodeURIComponent(search.value)}`;
    }
};

// const fetchCategories = async () => {
//   try {
//     const response = await axios.get('/api/categories');
//     categories.value = response.data;
//     console.log('Categories fetched:', categories.value);
//     isLoading.value = false;
//   } catch (err) {
//     console.error('Error fetching categories:', err);
//     error.value = 'Failed to load categories';
//     isLoading.value = false;
//   }
// };

const subscribeNewsletter = async () => {
    try {
        await axios.post("/api/newsletter/subscribe", {
            email: newsletterEmail.value,
        });
        newsletterEmail.value = "";
        // Add success notification here
    } catch (error) {
        console.error("Newsletter subscription failed:", error);
        // Add error notification here
    }
};

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
    currentSlide.value =
        (currentSlide.value - 1 + slides.length) % slides.length;
};

// Lifecycle hooks
onMounted(async () => {
    avatarUrl.value = getAvatarUrl();

    // Start slider
    slideInterval.value = setInterval(nextSlide, 5000);

    // Fetch categories
    await fetchCategories();
});

onBeforeUnmount(() => {
    if (slideInterval.value) {
        clearInterval(slideInterval.value);
    }
});

import {
    Sofa,
    Shirt,
    BookOpen,
    Dumbbell,
    Laptop,
    ShoppingBag,
    Car,
    Home,
    Palette,
    Music,
} from "lucide-vue-next";

// Add this mapping object after your other const declarations
const categoryIcons = {
    Furniture: Sofa,
    Clothing: Shirt,
    Books: BookOpen,
    Sports: Dumbbell,
    Electronics: Laptop,
    Fashion: ShoppingBag,
    Automotive: Car,
    "Home & Garden": Home,
    Art: Palette,
    Music: Music,
};

// Modify your fetchCategories function to format the data
const fetchCategories = async () => {
    try {
        const response = await axios.get("/api/categories");
        // Transform the array strings into objects with more properties
        categories.value = response.data.map((category) => ({
            id: category.toLowerCase().replace(/\s+/g, "-"),
            name: category,
            icon: categoryIcons[category] || ShoppingBag, // Default to ShoppingBag if no icon found
            products_count: 0, // You can update this if you have the data
            description: `Explore our ${category.toLowerCase()} collection`, // Add a description
            bgColor: getRandomColor(), // Add some variety to the cards
        }));
        console.log("Categories fetched:", categories.value);
        isLoading.value = false;
    } catch (err) {
        console.error("Error fetching categories:", err);
        error.value = "Failed to load categories";
        isLoading.value = false;
    }
};

// Add this helper function for random background colors
const getRandomColor = () => {
    const colors = [
        "from-purple-500 to-blue-500",
        "from-blue-500 to-teal-500",
        "from-teal-500 to-green-500",
        "from-orange-500 to-pink-500",
        "from-pink-500 to-purple-500",
    ];
    return colors[Math.floor(Math.random() * colors.length)];
};
</script>

<style scoped>
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
