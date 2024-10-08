<template>
    <div class="min-h-screen bg-gray-100">
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-2xl font-bold text-gray-900">
                                RSS Shop
                            </h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <DropdownMenu />
                    </div>
                </div>
            </div>
        </nav>

        <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <div class="px-4 py-6 sm:px-0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                Featured Categories
                            </h2>
                            <div class="mt-4 space-y-2">
                                <CategoryButton
                                    v-for="category in categories"
                                    :key="category"
                                    :name="category"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h2 class="text-lg font-medium text-gray-900">
                                Top Sellers
                            </h2>
                            <div class="mt-4 grid grid-cols-2 gap-4">
                                <BrandCard
                                    v-for="brand in topBrands"
                                    :key="brand.name"
                                    :brand="brand"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref } from "vue";
import DropdownMenu from "./DropdownMenu.vue";
import CategoryButton from "./CategoryButton.vue";
import BrandCard from "./BrandCard.vue";

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

const topBrands = [
    { name: "Spectra", logo: "/brands/spectra.png" },
    { name: "Mega Knight", logo: "/brands/mega-knight.png" },
    { name: "Baseus", logo: "/brands/baseus.png" },
    { name: "DJI", logo: "/brands/dji.png" },
];
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
