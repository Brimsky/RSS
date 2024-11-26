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
                        
                        
                    </div>
                </div>
            </div>
        </nav>

        <div v-if="product" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
                <!-- Back Button -->
                <Link
                    :href="
                        route('products.listings', {
                            category: product.category,
                            subcategory: product.subcategory,
                        })
                    "
                    class="flex items-center text-gray-600 hover:text-purple-600 transition-colors duration-300 mr-4"
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
                    Back to {{ product.subcategory }}
                </Link>

                <!-- Full Breadcrumb Trail -->
                <Link
                    :href="
                        route('products.subcategories', {
                            category: product.category,
                        })
                    "
                    class="hover:text-purple-600"
                >
                    {{ product.category }}
                </Link>
                <svg
                    class="w-4 h-4"
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
                <Link
                    :href="
                        route('products.listings', {
                            category: product.category,
                            subcategory: product.subcategory,
                        })
                    "
                    class="hover:text-purple-600"
                >
                    {{ product.subcategory }}
                </Link>
            </nav>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Column - Photos -->
                <div class="space-y-4">
                    <!-- Main Photo -->
                    <div class="bg-white p-4 rounded-2xl shadow-sm">
                        <img
                            :src="mainPhoto"
                            :alt="product.name"
                            @error="handleImageError"
                            class="w-full h-96 object-cover rounded-xl"
                        />
                    </div>

                    <!-- Thumbnail Grid -->
                    <div class="grid grid-cols-4 gap-4">
                        <div
                            v-for="(photo, index) in parsedPhotos"
                            :key="index"
                            class="aspect-square bg-white p-2 rounded-xl shadow-sm cursor-pointer hover:shadow-md transition-shadow duration-200"
                            @click="selectedPhoto = index"
                        >
                            <img
                                :src="photo"
                                :alt="`${product.name} - photo ${index + 1}`"
                                @error="handleImageError"
                                class="w-full h-full object-cover rounded-lg"
                            />
                        </div>
                    </div>
                </div>

                <!-- Right Column - Product Details -->
                <div
                    class="bg-white rounded-2xl shadow-sm p-6 lg:p-8 space-y-6"
                >
                    <!-- Title and Price -->
                    <div>
                        <h1
                            class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4"
                        >
                            {{ product.name }}
                        </h1>
                        <div class="flex items-center justify-between">
                            <p class="text-3xl font-bold text-purple-600">
                                ${{ formatPrice(product.price) }}
                            </p>
                            <div class="flex items-center space-x-2">
                                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-purple-600 font-medium">{{ product.seller.name.charAt(0).toUpperCase() }}</span>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-500">Seller</p>
                                    <p class="text-sm font-medium text-purple-600">{{ product.seller.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Info -->
                    <div class="flex flex-wrap gap-4">
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
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                />
                            </svg>
                            <span>{{ product.condition }}</span>
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
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                />
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                />
                            </svg>
                            <span>{{ product.location }}</span>
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
                        <!-- For example, in your saves count display -->
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
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"
                                />
                            </svg>
                            <span
                                >{{ productData.saves_count || 0 }} saves</span
                            >
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 mb-2">
                            Description
                        </h2>
                        <p class="text-gray-600">{{ product.description }}</p>
                    </div>

                    <!-- Details Grid -->
                    <div
                        class="grid grid-cols-2 gap-6 py-6 border-t border-b border-gray-100"
                    >
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Category</p>
                            <p class="font-medium">{{ product.category }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">
                                Subcategory
                            </p>
                            <p class="font-medium">{{ product.subcategory }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Condition</p>
                            <p class="font-medium">{{ product.condition }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Location</p>
                            <p class="font-medium">{{ product.location }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button
                            @click="contactSeller"
                            class="flex-1 bg-purple-600 text-white px-8 py-3 rounded-xl font-medium hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition-colors duration-300"
                        >
                            Contact Seller
                        </button>
                        <button
                            @click="addToCart(product)"
                            class="flex-1 bg-gray-100 text-gray-700 px-8 py-3 rounded-xl font-medium hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors duration-300"
                        >
                            Add to Cart
                        </button>
                        <!-- Save Button -->
                        <button
                            v-if="page.props.auth.user"
                            @click="saveProduct"
                            class="p-2 rounded hover:bg-gray-200 transition"
                            :class="{
                                'text-purple-600': productData.is_saved,
                                'text-gray-500': !productData.is_saved,
                            }"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
                                :fill="
                                    productData.is_saved
                                        ? 'currentColor'
                                        : 'none'
                                "
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
                        </button>
                        <Link
                            v-else
                            :href="route('login')"
                            class="p-2 rounded hover:bg-gray-200 transition text-gray-500"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-6 w-6"
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
                        </Link>
                    </div>

                    <!-- Posted Date -->
                    <div class="text-sm text-gray-500">
                        Posted {{ formatDate(product.created_at) }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Not Found State -->
        <div v-else class="max-w-md mx-auto text-center py-16">
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
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">
                Product Not Found
            </h3>
            <p class="mt-1 text-sm text-gray-500">
                We couldn't find the product you're looking for.
            </p>
            <div class="mt-6"></div>
        </div>
        <Toast v-if="toastMessage" :message="toastMessage" />
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import Toast from "@/Components/Toast.vue";
import axios from "axios";

const page = usePage();
const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

// Create a reactive product data object
const productData = ref({ ...props.product });

// Update saveProduct function
const saveProduct = async () => {
    if (!page.props.auth.user) {
        router.visit(route("login"));
        return;
    }

    try {
        const response = await axios.post(
            route("products.save", productData.value.id),
        );

        // Update the local state
        productData.value.is_saved = response.data.saved;
        productData.value.saves_count = response.data.saves_count;

        toastMessage.value = "Item hes been saved";
    } catch (error) {
        console.error("Error saving product:", error);
        const errorMessage =
            error.response?.status === 401
                ? "Please log in to save products"
                : "Error saving product";

        toastMessage.value = {
            type: "error",
            text: errorMessage,
        };
    } finally {
        setTimeout(() => {
            toastMessage.value = null;
        }, 3000);
    }
};

const parsedPhotos = computed(() => {
    if (!props.product?.photos) return [];
    try {
        return typeof props.product.photos === 'string' 
            ? JSON.parse(props.product.photos) 
            : props.product.photos;
    } catch (e) {
        console.error('Error parsing photos:', e);
        return [];
    }
});

const mainPhoto = computed(() => {
    return parsedPhotos.value[0] || 'https://via.placeholder.com/400';
});

const handleImageError = (event) => {
    event.target.src = 'https://via.placeholder.com/400';
};

const selectedPhoto = ref(null);
const toastMessage = ref(null);

const addToCart = (product) => {
    router.post(
        route("cart.add"),
        {
            product_id: product.id,
            quantity: 1,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                toastMessage.value = "Product added to cart!";
                setTimeout(() => {
                    toastMessage.value = null;
                }, 3000);
            },
            onError: (errors) => {
                toastMessage.value = "Error adding product to cart!";
                setTimeout(() => {
                    toastMessage.value = null;
                }, 3000);
                console.error("Error adding product to cart:", errors);
            },
        },
    );
};

const contactSeller = () => {
    if (!page.props.auth.user) {
        router.visit(route('login'));
        return;
    }
    
    // Prevent messaging yourself
    if (props.product?.user_id === page.props.auth.user.id) {
        alert('You cannot message yourself about your own product');
        return;
    }
    
    if (!props.product?.user_id) {
        console.error('Seller ID not found in product data');
        return;
    }
    
    const productInfo = {
        id: props.product.id,
        name: props.product.name,
        price: props.product.price,
        description: props.product.description,
        photo: parsedPhotos.value?.[0] || null
    };
    
    const encodedProduct = encodeURIComponent(JSON.stringify(productInfo));
    
    router.visit(route('chat.index', { 
        sellerId: props.product.user_id,
        product: encodedProduct
    }));
};


const formatPrice = (price) => {
    const numPrice = Number(price);
    return isNaN(numPrice) ? "0.00" : numPrice.toFixed(2);
};

const formatDate = (date) => {
    return new Intl.DateTimeFormat("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
    }).format(new Date(date));
};

</script>

<style scoped>
.aspect-w-4 {
    position: relative;
    padding-bottom: 75%;
}

.aspect-square {
    position: relative;
    padding-bottom: 100%;
}

.aspect-w-4 > img,
.aspect-square > img {
    position: absolute;
    height: 100%;
    width: 100%;
    top: 0;
    left: 0;
    object-fit: cover;
}
</style>
