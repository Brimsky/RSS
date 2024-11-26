<template>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Header Section with Back Button -->
        <div class="mb-4">
            <!-- Back Button -->
            <Link
                :href="route('products.subcategories', { category })"
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
                Back to {{ category }}
            </Link>
        </div>
        <!-- Header Section -->
        <div class="mb-8">
            <h1
                class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent"
            >
                {{ category }}
            </h1>
            <div class="flex items-center text-gray-600 mt-2">
                <span>{{ subcategory }}</span>
                <span class="mx-2">•</span>
                <span>{{ filteredProducts.length }} items</span>
            </div>
        </div>

        <!-- Filter and sorting controls -->
        <div class="mb-8 space-y-4">
            <div class="flex space-x-4">
                <input
                    v-model="searchQuery"
                    class="border rounded p-2 flex-grow"
                    placeholder="Search products..."
                    @input="filterProducts"
                />
                <select
                    v-model="sortBy"
                    class="border rounded p-2"
                    @change="sortProducts"
                >
                    <option value="name">Sort by Name</option>
                    <option value="price">Sort by Price</option>
                    <option value="date">Sort by Date Added</option>
                </select>
                <button
                    @click="toggleSortOrder"
                    class="bg-gray-200 hover:bg-gray-300 rounded p-2"
                >
                    {{ sortOrder === "asc" ? "↑" : "↓" }}
                </button>
            </div>

            <div class="flex space-x-4">
                <div>
                    <label class="mr-2">Min Price:</label>
                    <input
                        v-model="minPrice"
                        type="number"
                        class="border rounded p-2 w-24"
                        @input="filterProducts"
                    />
                </div>
                <div>
                    <label class="mr-2">Max Price:</label>
                    <input
                        v-model="maxPrice"
                        type="number"
                        class="border rounded p-2 w-24"
                        @input="filterProducts"
                    />
                </div>
            </div>
        </div>

        <!-- No Products Message -->
        <div
            v-if="filteredProducts.length === 0"
            class="bg-white rounded-xl shadow-sm p-8"
        >
            <div class="text-center">
                <svg
                    class="mx-auto h-12 w-12 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                    />
                </svg>
                <p class="mt-4 text-lg font-medium text-gray-900">
                    No products found
                </p>
                <p class="mt-2 text-gray-500">
                    We couldn't find any products in this category and
                    subcategory.
                </p>
            </div>
        </div>

        <!-- Products List -->
        <div v-else class="space-y-4">
            <Link
                v-for="product in filteredProducts.slice(0, displayLimit)"
                :key="product.id"
                :href="route('products.show', product.id)"
                @click="registerClick(product.id)"
                class="block bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300"
            >
                <div class="flex items-center p-4 sm:p-6">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        <img
                            :src="getProductPhoto(product.photos)"
                            :alt="product.name"
                            @error="handleImageError"
                            class="w-24 h-24 object-cover rounded-lg"
                        />
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1 min-w-0 px-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <h2
                                class="text-lg font-semibold text-gray-900 truncate"
                            >
                                {{ product.name }}
                            </h2>
                            <div class="text-lg font-bold text-purple-600">
                                €{{ formatPrice(product.price) }}
                            </div>
                        </div>
                        <div class="mt-1 flex items-center text-sm text-gray-500">
                            <span class="truncate">{{ product.description }}</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm">
                            <span class="text-gray-600 mr-2">Seller:</span>
                            <span class="font-medium text-purple-600">{{ product.user.name }}</span>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 mr-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                <span
                                    >Listed
                                    {{ formatDate(product.created_at) }}</span
                                >
                            </div>

                            <div class="flex items-center">
                                <svg
                                    class="w-4 h-4 mr-1"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                <span>{{
                                    product.location || "Location not specified"
                                }}</span>
                            </div>

                            <div
                                class="flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full"
                            >
                                <svg
                                    class="w-4 h-4 mr-2"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                                <span>{{ product.clicks || 0 }} views</span>
                            </div>
                        </div>
                    </div>
                </div>
            </Link>

            <!-- Load more button -->
            <div class="mt-6 flex justify-between items-center">
                <button
                    @click="loadMore"
                    v-if="hasMoreProducts"
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                >
                    Load More
                </button>
                <p class="text-gray-600">
                    Showing
                    {{ filteredProducts.slice(0, displayLimit).length }} of
                    {{ filteredProducts.length }} products
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    category: String,
    subcategory: String,
    products: Array,
});

const searchQuery = ref("");
const sortBy = ref("name");
const sortOrder = ref("asc");
const minPrice = ref("");
const maxPrice = ref("");
const filteredProducts = ref([...props.products]);
const displayLimit = ref(9);

const formatPrice = (price) => {
    const numPrice = Number(price);
    return isNaN(numPrice) ? "0.00" : numPrice.toFixed(2);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString("en-US", {
        month: "short",
        day: "numeric",
        year: "numeric",
    });
};

const filterProducts = () => {
    filteredProducts.value = props.products.filter(
        (product) =>
            (product.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
                product.description
                    .toLowerCase()
                    .includes(searchQuery.value.toLowerCase())) &&
            (minPrice.value === "" ||
                product.price >= Number(minPrice.value)) &&
            (maxPrice.value === "" || product.price <= Number(maxPrice.value)),
    );
    sortProducts();
};

const sortProducts = () => {
    filteredProducts.value.sort((a, b) => {
        let comparison = 0;
        if (sortBy.value === "name") {
            comparison = a.name.localeCompare(b.name);
        } else if (sortBy.value === "price") {
            comparison = a.price - b.price;
        } else if (sortBy.value === "date") {
            comparison = new Date(b.dateAdded) - new Date(a.dateAdded);
        }
        return sortOrder.value === "asc" ? comparison : -comparison;
    });
};

const toggleSortOrder = () => {
    sortOrder.value = sortOrder.value === "asc" ? "desc" : "asc";
    sortProducts();
};

const loadMore = () => {
    displayLimit.value += 9;
};

const hasMoreProducts = computed(() => {
    return filteredProducts.value.length > displayLimit.value;
});

const registerClick = async (productId) => {
    try {
        await axios.post(`/products/${productId}/register-click`);
    } catch (error) {
        console.error("Error registering click:", error);
    }
};

const getProductPhoto = (photos) => {
    if (!photos) return 'https://via.placeholder.com/300';
    try {
        const parsedPhotos = typeof photos === 'string' ? JSON.parse(photos) : photos;
        return Array.isArray(parsedPhotos) && parsedPhotos.length > 0
            ? parsedPhotos[0]
            : 'https://via.placeholder.com/300';
    } catch (e) {
        console.error('Error parsing photos:', e);
        return 'https://via.placeholder.com/300';
    }
};

const handleImageError = (event) => {
    event.target.src = 'https://via.placeholder.com/300';
};

// Initial filter and sort
filterProducts();
</script>

<style scoped>
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
