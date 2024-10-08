<template>
    <div class="min-h-screen bg-gradient">
        <nav class="bg-white/80 backdrop-blur-sm shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-2xl font-bold text-gray-900">
                                RSS Market
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <div class="relative ml-3">
                            <button
                                @click="isOpen = !isOpen"
                                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300"
                            >
                                Account
                            </button>

                            <div
                                v-if="isOpen"
                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white/80 backdrop-blur-sm ring-1 ring-black ring-opacity-5"
                            >
                                <div class="py-1">
                                    <a
                                        href="/login"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >Login</a
                                    >
                                    <a
                                        href="/register"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                        >Register</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Categories -->
                <div class="col-span-1">
                    <div
                        class="bg-white/80 backdrop-blur-sm shadow rounded-lg p-4 transition-all duration-300 hover:bg-white/90"
                    >
                        <h2 class="text-lg font-semibold mb-4 text-gray-800">
                            Categories
                        </h2>
                        <ul class="space-y-2">
                            <li
                                v-for="category in categories"
                                :key="category"
                                class="p-2 hover:bg-white/60 rounded cursor-pointer transition-all duration-300"
                            >
                                {{ category }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main content -->
                <div class="col-span-1 md:col-span-3">
                    <!-- Interactive Banner -->
                    <div
                        class="bg-white/80 backdrop-blur-sm shadow rounded-lg p-4 mb-4 transition-all duration-300 hover:bg-white/90"
                    >
                        <div class="relative h-64 overflow-hidden rounded-lg">
                            <template
                                v-for="(slide, index) in slides"
                                :key="index"
                            >
                                <transition
                                    enter-active-class="transition-opacity duration-500"
                                    leave-active-class="transition-opacity duration-500"
                                    enter-from-class="opacity-0"
                                    leave-to-class="opacity-0"
                                >
                                    <div
                                        v-if="currentSlide === index"
                                        class="absolute inset-0 flex items-center justify-between p-4"
                                        :style="{
                                            backgroundColor: slide.bgColor,
                                        }"
                                    >
                                        <div class="text-white ml-4">
                                            <h2 class="text-2xl font-bold mb-2">
                                                {{ slide.title }}
                                            </h2>
                                            <p class="mb-4">
                                                {{ slide.description }}
                                            </p>
                                            <button
                                                class="bg-white text-black px-4 py-2 rounded-md hover:bg-gray-100 transition-all duration-300"
                                            >
                                                {{ slide.buttonText }}
                                            </button>
                                        </div>
                                        <div
                                            class="w-1/2 h-full flex items-center justify-center"
                                        >
                                            <img
                                                :src="slide.image"
                                                :alt="slide.title"
                                                class="max-h-full object-contain"
                                            />
                                        </div>
                                    </div>
                                </transition>
                            </template>

                            <!-- Navigation dots -->
                            <div
                                class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2"
                            >
                                <button
                                    v-for="(_, index) in slides"
                                    :key="index"
                                    @click="currentSlide = index"
                                    class="w-3 h-3 rounded-full transition-all duration-300"
                                    :class="
                                        currentSlide === index
                                            ? 'bg-white'
                                            : 'bg-white/50'
                                    "
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

                    <div
                        class="bg-white/80 backdrop-blur-sm shadow rounded-lg p-4 transition-all duration-300 hover:bg-white/90"
                    >
                        <!-- Top Sellers section -->
                        <div class="mb-8">
                            <h2
                                class="text-xl font-semibold mb-4 text-gray-800"
                            >
                                Top Sellers
                            </h2>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div
                                    v-for="seller in topSellers"
                                    :key="seller"
                                    class="p-4 border rounded-lg text-center bg-white/50 hover:bg-white/80 transition-all duration-300"
                                >
                                    <div
                                        class="w-16 h-16 mx-auto mb-2 bg-gray-200 rounded-full"
                                    ></div>
                                    <p class="font-medium text-gray-800">
                                        {{ seller }}
                                    </p>
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
        bgColor: "#FF4444",
        image: "/api/placeholder/300/200",
    },
    {
        title: "Summer Sale",
        description: "Up to 50% off",
        buttonText: "View Deals",
        bgColor: "#4444FF",
        image: "/api/placeholder/300/200",
    },
    {
        title: "New Arrivals",
        description: "Check out our latest products",
        buttonText: "Discover",
        bgColor: "#44AA44",
        image: "/api/placeholder/300/200",
    },
];

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.length;
};

const prevSlide = () => {
    currentSlide.value =
        (currentSlide.value - 1 + slides.length) % slides.length;
};

let slideInterval;

onMounted(() => {
    slideInterval = setInterval(nextSlide, 5000);
});

onBeforeUnmount(() => {
    clearInterval(slideInterval);
});
</script>

<style>
.bg-gradient {
    background: linear-gradient(135deg, #f6f8fb 0%, #f1f5f9 100%);
}

/* Add these styles to your global CSS or scoped styles */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
}
</style>
