<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100 py-6">
            <div class="container">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Product List</h1>
                    <!-- Show "Add New Product" button for seller and admin roles -->
                    <template
                        v-if="
                            $page.props.auth.user.role === 'seller' ||
                            $page.props.auth.user.role === 'admin'
                        "
                    >
                        <Link
                            :href="route('products.create')"
                            class="btn btn-primary"
                        >
                            Add New Product
                        </Link>
                    </template>
                </div>

                <!-- Grid Product Layout -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white p-4 rounded-lg shadow-lg relative"
                    >
                        <!-- Product Image -->
                        <div class="relative">
                            <img
                                :src="getFirstPhoto(product.photos)"
                                :alt="product.name"
                                @error="handleImageError"
                                class="w-full h-32 object-cover mb-4 rounded-md"
                            />
                            <!-- View Count Badge -->
                            <div
                                class="absolute top-2 right-2 bg-black/50 text-white px-2 py-1 rounded-full text-xs"
                            >
                                {{ product.clicks || 0 }} views
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="space-y-2">
                            <!-- Product Name -->
                            <h2 class="font-semibold text-lg">
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="text-blue-600 hover:underline"
                                    @click="registerClick(product.id)"
                                >
                                    {{ product.name }}
                                </Link>
                            </h2>

                            <!-- Product Price -->
                            <p class="text-gray-600 text-xl font-bold">
                                ${{ formatPrice(product.price) }}
                            </p>

                            <!-- Category & Location -->
                            <div
                                class="flex items-center text-sm text-gray-500 space-x-2"
                            >
                                <span class="bg-gray-100 px-2 py-1 rounded">{{
                                    product.category
                                }}</span>
                                <span>·</span>
                                <span class="bg-gray-100 px-2 py-1 rounded">{{
                                    product.location
                                }}</span>
                            </div>

                            <!-- Product Condition -->
                            <p class="text-sm text-gray-500">
                                Condition: {{ product.condition }}
                            </p>

                            <!-- Product Description -->
                            <p class="text-sm text-gray-500 line-clamp-2">
                                {{ product.description }}
                            </p>

                            <!-- Posted Date -->
                            <p class="text-xs text-gray-400">
                                Posted {{ formatDate(product.created_at) }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="mt-4 space-y-2">
                            <!-- Seller/Admin Actions -->
                            <template
                                v-if="
                                    $page.props.auth.user.role === 'seller' ||
                                    $page.props.auth.user.role === 'admin'
                                "
                            >
                                <div class="flex space-x-2">
                                    <Link
                                        :href="
                                            route('products.edit', product.id)
                                        "
                                        class="btn btn-warning flex-1 text-center"
                                        >Edit</Link
                                    >
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="btn btn-danger flex-1"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Toast v-if="toastMessage" :message="toastMessage" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import Toast from "@/Components/Toast.vue";
import axios from "axios";

const props = defineProps({
    products: Array,
});

const toastMessage = ref(null);

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

const getFirstPhoto = (photos) => {
    if (!photos) return "https://via.placeholder.com/300";
    
    try {
        const parsedPhotos = typeof photos === 'string' ? JSON.parse(photos) : photos;
        if (Array.isArray(parsedPhotos) && parsedPhotos.length > 0) {
            return parsedPhotos[0];
        }
    } catch (e) {
        console.error('Error parsing photos:', e);
    }
    
    return "https://via.placeholder.com/300";
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

const deleteProduct = (id) => {
    if (confirm("Are you sure you want to delete this product?")) {
        router.delete(route("products.destroy", id), {
            preserveState: false,
            onSuccess: () => showToast("Product deleted successfully"),
            onError: () => showToast("Error deleting product", "error"),
        });
    }
};

const showToast = (message, type = "success") => {
    toastMessage.value = { text: message, type };
    setTimeout(() => {
        toastMessage.value = null;
    }, 3000);
};
</script>

<style scoped>
/* Container styling */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

/* Button styling */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition:
        background-color 0.3s,
        transform 0.2s;
}

.btn-primary {
    background-color: #4299e1;
    color: white;
}

.btn-primary:hover {
    background-color: #3182ce;
    transform: translateY(-1px);
}

.btn-warning {
    background-color: #ed8936;
    color: white;
}

.btn-warning:hover {
    background-color: #dd6b20;
}

.btn-danger {
    background-color: #f56565;
    color: white;
}

.btn-danger:hover {
    background-color: #e53e3e;
}

/* Text truncation */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
