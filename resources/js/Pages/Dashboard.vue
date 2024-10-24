<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Seller Role -->
                    <template v-if="$page.props.auth.user.role === 'seller'">
                        <!-- Seller Stats and Listings -->
                        <div class="space-y-8">
                            <!-- Stats Card -->
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-lg p-6"
                            >
                                <div
                                    class="flex justify-between items-center mb-6"
                                >
                                    <h2
                                        class="text-2xl font-bold text-gray-900"
                                    >
                                        Seller Dashboard
                                    </h2>
                                    <div class="flex items-center">
                                        <span class="text-4xl font-bold mr-2">{{
                                            averageRating
                                        }}</span>
                                        <div class="flex -space-x-1">
                                            <svg
                                                class="h-8 w-8 text-yellow-400"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="grid grid-cols-2 gap-6 mb-6">
                                    <div
                                        class="bg-gray-50 rounded-xl p-4 text-center"
                                    >
                                        <div
                                            class="text-4xl font-bold text-purple-600 mb-1"
                                        >
                                            {{ products.length }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            Active Listings
                                        </div>
                                    </div>
                                    <div
                                        class="bg-gray-50 rounded-xl p-4 text-center"
                                    >
                                        <div
                                            class="text-4xl font-bold text-purple-600 mb-1"
                                        >
                                            {{ totalClicks }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            Total Views
                                        </div>
                                    </div>
                                </div>

                                <!-- Create Listing Button -->
                                <Link
                                    :href="route('products.create')"
                                    class="block w-full text-center bg-purple-600 text-white px-6 py-3 rounded-xl text-lg font-medium hover:bg-purple-700 transition-colors duration-300"
                                >
                                    <span
                                        class="flex items-center justify-center"
                                    >
                                        <svg
                                            class="w-5 h-5 mr-2"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                            />
                                        </svg>
                                        Create New Listing
                                    </span>
                                </Link>
                            </div>

                            <!-- Active Listings -->
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-lg p-6 relative"
                            >
                                <h3
                                    class="text-xl font-bold text-gray-900 mb-6"
                                >
                                    Active Listings
                                </h3>
                                <div class="space-y-6 mb-16">
                                    <div
                                        v-for="product in limitedProducts"
                                        :key="product.id"
                                        class="group border rounded-xl p-4 hover:shadow-lg transition-all duration-300"
                                    >
                                        <!-- Product Info -->
                                        <div class="flex gap-4">
                                            <!-- Product Image -->
                                            <div
                                                class="relative w-32 h-32 flex-shrink-0"
                                            >
                                                <div
                                                    class="absolute inset-0 rounded-lg overflow-hidden"
                                                >
                                                    <img
                                                        :src="
                                                            getFirstPhoto(
                                                                product.photos,
                                                            )
                                                        "
                                                        :alt="product.name"
                                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-300"
                                                        @error="
                                                            handleImageError
                                                        "
                                                    />
                                                </div>
                                            </div>

                                            <div class="flex-grow min-w-0">
                                                <div
                                                    class="flex justify-between items-start gap-4"
                                                >
                                                    <div>
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'products.show',
                                                                    product.id,
                                                                )
                                                            "
                                                            class="text-lg font-semibold text-gray-900 hover:text-purple-600 transition-colors duration-300"
                                                            @click="
                                                                registerClick(
                                                                    product.id,
                                                                )
                                                            "
                                                        >
                                                            {{ product.name }}
                                                        </Link>
                                                        <p
                                                            class="text-2xl font-bold text-purple-600 mt-1"
                                                        >
                                                            ${{
                                                                formatPrice(
                                                                    product.price,
                                                                )
                                                            }}
                                                        </p>
                                                    </div>
                                                </div>

                                                <!-- Product Metadata -->
                                                <div
                                                    class="mt-3 flex flex-wrap gap-2"
                                                >
                                                    <span
                                                        class="inline-flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full"
                                                    >
                                                        {{ product.condition }}
                                                    </span>
                                                    <span
                                                        class="inline-flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full"
                                                        >{{
                                                            product.location
                                                        }}</span
                                                    >
                                                    <span
                                                        class="inline-flex items-center text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full"
                                                        >{{
                                                            product.clicks || 0
                                                        }}
                                                        views</span
                                                    >
                                                </div>

                                                <p
                                                    class="mt-2 text-sm text-gray-500 line-clamp-2"
                                                >
                                                    {{ product.description }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Seller Actions -->
                                        <div
                                            class="mt-4 flex justify-end space-x-3"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'products.edit',
                                                        product.id,
                                                    )
                                                "
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                            >
                                                Edit
                                            </Link>
                                            <button
                                                @click="markAsSold(product.id)"
                                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-purple-700 bg-purple-50 hover:bg-purple-100"
                                            >
                                                Mark as sold
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- View All Link -->
                                <Link
                                    :href="route('products.index')"
                                    class="absolute bottom-6 right-6 bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700"
                                >
                                    View all
                                </Link>
                            </div>
                        </div>

                        <!-- Product Performance and Insights -->
                        <div class="space-y-8">
                            <div
                                class="bg-white overflow-hidden shadow-lg rounded-lg p-6"
                            >
                                <h2
                                    class="text-2xl font-bold text-gray-900 mb-6"
                                >
                                    Marketplace Insights
                                </h2>
                                <div class="space-y-6">
                                    <!-- Views -->
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div
                                                class="flex items-center space-x-4"
                                            >
                                                <div
                                                    class="p-2 bg-purple-100 rounded-lg"
                                                >
                                                    <svg
                                                        class="h-6 w-6 text-purple-600"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-sm text-gray-600"
                                                    >
                                                        Total Views
                                                    </p>
                                                    <p
                                                        class="text-2xl font-bold text-gray-900"
                                                    >
                                                        {{ totalClicks }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Saved Products Count -->
                                    <div class="bg-gray-50 rounded-xl p-4">
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <div
                                                class="flex items-center space-x-4"
                                            >
                                                <div
                                                    class="p-2 bg-purple-100 rounded-lg"
                                                >
                                                    <svg
                                                        class="h-6 w-6 text-purple-600"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p
                                                        class="text-sm text-gray-600"
                                                    >
                                                        Listing Saves
                                                    </p>
                                                    <p
                                                        class="text-2xl font-bold text-gray-900"
                                                    >
                                                        {{ savedProductCount }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Popular Products -->
                                    <div class="space-y-4">
                                        <h3 class="font-semibold text-gray-900">
                                            Popular Products
                                        </h3>
                                        <div class="space-y-3">
                                            <div
                                                v-for="product in topPerformingProducts"
                                                :key="product.id"
                                                class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100"
                                            >
                                                <img
                                                    :src="
                                                        getFirstPhoto(
                                                            product.photos,
                                                        )
                                                    "
                                                    class="w-12 h-12 object-cover rounded-md mx-3"
                                                />
                                                <div class="flex-grow">
                                                    <h4
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        {{ product.name }}
                                                    </h4>
                                                    <p
                                                        class="text-sm text-gray-500"
                                                    >
                                                        {{
                                                            product.clicks
                                                        }}
                                                        views
                                                    </p>
                                                </div>
                                                <div class="text-right">
                                                    <p
                                                        class="text-sm font-medium text-gray-900"
                                                    >
                                                        ${{
                                                            formatPrice(
                                                                product.price,
                                                            )
                                                        }}
                                                    </p>
                                                    <p
                                                        class="text-xs text-green-600"
                                                    >
                                                        +{{ product.growth }}%
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Buyer Role -->
                    <template
                        v-else-if="$page.props.auth.user.role === 'buyer'"
                    >
                        <div
                            class="bg-white overflow-hidden shadow-lg rounded-lg p-6"
                        >
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                                Your Saved Products
                            </h2>
                            <div class="space-y-4">
                                <div
                                    v-if="!savedProducts?.length"
                                    class="text-center py-8 text-gray-500"
                                >
                                    <p>You haven't saved any products yet.</p>
                                    <Link
                                        :href="
                                            route('products.subcategories', {
                                                category: 'all',
                                            })
                                        "
                                        class="text-purple-600 hover:text-purple-700 mt-2 inline-block"
                                        >Browse Products</Link
                                    >
                                </div>

                                <div
                                    v-else
                                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                                >
                                    <div
                                        v-for="product in savedProducts"
                                        :key="product.id"
                                        class="bg-white rounded-lg border hover:shadow-lg transition-shadow duration-300"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'products.show',
                                                    product.id,
                                                )
                                            "
                                        >
                                            <div
                                                class="aspect-w-16 aspect-h-9 rounded-t-lg overflow-hidden"
                                            >
                                                <img
                                                    :src="
                                                        getFirstPhoto(
                                                            product.photos,
                                                        )
                                                    "
                                                    :alt="product.name"
                                                    class="w-full h-48 object-cover"
                                                    @error="handleImageError"
                                                />
                                            </div>
                                            <div class="p-4">
                                                <h3
                                                    class="font-semibold text-lg mb-2"
                                                >
                                                    {{ product.name }}
                                                </h3>
                                                <p
                                                    class="text-purple-600 font-bold mb-2"
                                                >
                                                    ${{
                                                        formatPrice(
                                                            product.price,
                                                        )
                                                    }}
                                                </p>
                                                <div
                                                    class="flex flex-wrap gap-2"
                                                >
                                                    <span
                                                        class="inline-flex items-center text-sm text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full"
                                                        >{{
                                                            product.condition
                                                        }}</span
                                                    >
                                                    <span
                                                        class="inline-flex items-center text-sm text-gray-500 bg-gray-50 px-2.5 py-1 rounded-full"
                                                        >{{
                                                            product.location
                                                        }}</span
                                                    >
                                                </div>
                                                <p
                                                    class="mt-2 text-sm text-gray-500 line-clamp-2"
                                                >
                                                    {{ product.description }}
                                                </p>
                                            </div>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";

// Props
const props = defineProps({
    products: Array,
    savedProducts: Array,
    totalClicks: Number,
    savedProductCount: Number,
    averageRating: Number,
});

// Reactive state
const limit = ref(3);

// Computed properties
const limitedProducts = computed(() => props.products.slice(0, limit.value));

const topPerformingProducts = computed(() => {
    return [...props.products]
        .sort((a, b) => (b.clicks || 0) - (a.clicks || 0))
        .slice(0, 5)
        .map((product) => ({
            ...product,
            growth: Math.floor(Math.random() * 20) + 1, // Mock growth data
        }));
});

// Methods
const formatPrice = (price) => Number(price).toFixed(2);
const formatDate = (date) =>
    new Intl.DateTimeFormat("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
    }).format(new Date(date));
const getFirstPhoto = (photos) => {
    try {
        const parsedPhotos = JSON.parse(photos);
        return parsedPhotos[0] || "https://via.placeholder.com/300";
    } catch {
        return "https://via.placeholder.com/300";
    }
};
const handleImageError = (event) => {
    event.target.src = "https://via.placeholder.com/300";
};
const registerClick = async (productId) => {
    try {
        await axios.post(`/products/${productId}/register-click`);
    } catch (error) {
        console.error("Error registering click:", error);
    }
};
const markAsSold = async (productId) => {
    try {
        await axios.post(`/products/${productId}/mark-as-sold`);
    } catch (error) {
        console.error("Error marking product as sold:", error);
    }
};
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
