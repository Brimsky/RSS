<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section with Back Button -->
            <div class="mb-4">
                <!-- Back Button -->
                <a
                    href="/"
                    class="flex items-center text-gray-600 hover:text-purple-600 transition-colors duration-300"
                >
                    <svg
                        class="h-5 w-5 mr-2"
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
                    Back
                </a>
            </div>
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1
                    class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent inline-block"
                >
                    {{ category }}
                </h1>
                <div
                    class="mt-2 flex items-center justify-center text-gray-600"
                >
                    <svg
                        class="h-5 w-5"
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
                    <span class="ml-2 text-xl">{{
                        subcategory ? subcategory : "Subcategories"
                    }}</span>
                </div>
            </div>

            <!-- Subcategories Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="subcategory in subcategories"
                    :key="subcategory"
                    :href="
                        route('products.listings', { category, subcategory })
                    "
                    class="group relative bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col"
                >
                    <!-- Card Content -->
                    <div class="p-6 flex items-center">
                        <!-- Icon -->
                        <div
                            class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-blue-100 group-hover:from-purple-200 group-hover:to-blue-200 flex items-center justify-center transition-colors duration-300"
                        >
                            <component
                                :is="getCategoryIcon(subcategory)"
                                class="w-6 h-6 text-purple-600 group-hover:text-purple-700 transition-colors duration-300"
                            />
                        </div>

                        <!-- Title -->
                        <h2
                            class="ml-4 text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors duration-300"
                        >
                            {{ subcategory }}
                        </h2>

                        <!-- Arrow Icon -->
                        <svg
                            class="w-5 h-5 ml-auto text-gray-400 group-hover:text-purple-600 group-hover:translate-x-1 transition-all duration-300"
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

                    <!-- Bottom Gradient Bar -->
                    <div
                        class="h-1 bg-gradient-to-r from-purple-500 to-blue-500 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300"
                    ></div>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue";

import {
    Laptop,
    Smartphone,
    Monitor,
    Keyboard,
    Mouse,
    Headphones,
    Camera,
    Printer,
    HardDrive,
    Cpu,
    Wifi,
    Battery,
    ShoppingBag,
    Shirt,
    Watch,
    Glasses,
    Book,
    BookOpen,
    Library,
    Dumbbell,
    Bike,
    Trophy,
    Music,
    Palette,
    Image,
    Video,
    Home,
    Sofa,
    Bed,
    Lamp,
    Car,
    Wrench,
    Container,
} from "lucide-vue-next";

const props = defineProps({
    category: String,
    subcategory: String,
    subcategories: Array,
    products: Array,
    message: String,
});

// Function to get icon based on subcategory and main category
const getCategoryIcon = (subcategory) => {
    const normalizedSubcategory = subcategory.toLowerCase();
    const normalizedCategory = props.category.toLowerCase();

    // Electronics icons
    if (normalizedCategory === "electronics") {
        if (normalizedSubcategory.includes("laptop")) return Laptop;
        if (normalizedSubcategory.includes("phone")) return Smartphone;
        if (normalizedSubcategory.includes("monitor")) return Monitor;
        if (normalizedSubcategory.includes("keyboard")) return Keyboard;
        if (normalizedSubcategory.includes("mouse")) return Mouse;
        if (normalizedSubcategory.includes("audio")) return Headphones;
        if (normalizedSubcategory.includes("camera")) return Camera;
        if (normalizedSubcategory.includes("printer")) return Printer;
        if (normalizedSubcategory.includes("storage")) return HardDrive;
        if (normalizedSubcategory.includes("processor")) return Cpu;
        if (normalizedSubcategory.includes("network")) return Wifi;
        return Cpu; // Default electronics icon
    }

    // Clothing/Fashion icons
    if (normalizedCategory === "clothing" || normalizedCategory === "fashion") {
        if (normalizedSubcategory.includes("shirt")) return Shirt;
        if (normalizedSubcategory.includes("accessory")) return Watch;
        if (normalizedSubcategory.includes("glasses")) return Glasses;
        return ShoppingBag; // Default fashion icon
    }

    // Books icons
    if (normalizedCategory === "books") {
        if (normalizedSubcategory.includes("fiction")) return Book;
        if (normalizedSubcategory.includes("education")) return BookOpen;
        if (normalizedSubcategory.includes("library")) return Library;
        return Book; // Default book icon
    }

    // Sports icons
    if (normalizedCategory === "sports") {
        if (normalizedSubcategory.includes("fitness")) return Dumbbell;
        if (normalizedSubcategory.includes("football")) return Football;
        if (normalizedSubcategory.includes("cycling")) return Bike;
        if (normalizedSubcategory.includes("trophy")) return Trophy;
        return Dumbbell; // Default sports icon
    }

    // Art icons
    if (normalizedCategory === "art") {
        if (normalizedSubcategory.includes("music")) return Music;
        if (normalizedSubcategory.includes("paint")) return Palette;
        if (normalizedSubcategory.includes("photo")) return Image;
        if (normalizedSubcategory.includes("video")) return Video;
        return Palette; // Default art icon
    }

    // Home icons
    if (normalizedCategory === "home") {
        if (normalizedSubcategory.includes("furniture")) return Sofa;
        if (normalizedSubcategory.includes("bed")) return Bed;
        if (normalizedSubcategory.includes("light")) return Lamp;
        return Home; // Default home icon
    }

    // Automotive icons
    if (normalizedCategory === "automotive") {
        if (normalizedSubcategory.includes("parts")) return Car;
        if (normalizedSubcategory.includes("tool")) return Tool;
        if (normalizedSubcategory.includes("repair")) return Wrench;
        return Car; // Default automotive icon
    }

    // Default icon for any other category
    return Container;
};
</script>

<style scoped>
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

/* Smooth transitions */
.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
