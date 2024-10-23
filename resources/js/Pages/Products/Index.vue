<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100 py-6">
            <div class="container">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Product List
                    </h1>
                    <template v-if="$page.props.auth.user.role === 'seller' || $page.props.auth.user.role === 'admin'">

                        <Link
                            :href="route('products.create')"
                            class="btn btn-primary"
                        >
                            Add New Product
                        </Link>
                    </template>
                </div>

                <!-- Grid Product Layout -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white p-4 rounded-lg shadow-lg relative"
                    >
                        <!-- Product Image -->
                        <img
                            v-if="product.image"
                            :src="product.image"
                            alt="Product Image"
                            class="w-full h-32 object-cover mb-4 rounded-md"
                        />

                        <!-- Product Name -->
                        <h2 class="font-semibold text-lg mb-2">
                            <Link
                                :href="route('products.show', product.id)"
                                class="text-blue-600 hover:underline"
                                @click="registerClick(product.id)"
                            >
                                {{ product.name }}
                            </Link>
                        </h2>

                        <!-- Product Price -->
                        <p class="text-gray-600 mb-4">
                            ${{ parseFloat(product.price).toFixed(2) }}
                        </p>

                        <!-- Product Description -->
                        <p class="text-sm text-gray-500 mb-4">
                            {{ product.description }}
                        </p>

                        <!-- Actions -->
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <!-- Add to Cart Button -->
                                <button
                                    @click="addToCart(product)"
                                    class="btn btn-primary"
                                >
                                    Add to Cart
                                </button>

                                <!-- Listing Saves Icon -->
                                <button
                                    @click="saveProduct(product)"
                                    class="p-1 rounded hover:bg-gray-200 transition"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 text-gray-500"
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
                                </button>
                            </div>

                            <!-- Edit/Delete Actions for Seller -->
                            <template v-if="$page.props.auth.user.role === 'seller' || $page.props.auth.user.role === 'admin'">
                                <div class="flex space-x-2">
                                    <Link
                                        :href="route('products.edit', product.id)"
                                        class="btn btn-warning"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="btn btn-danger"
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

const registerClick = async (productId) => {
    try {
        await axios.post(`/products/${productId}/register-click`);
    } catch (error) {
        console.error("Error registering click:", error);
    }
};

const saveProduct = async (product) => {
    try {
        await axios.post(`/products/${product.id}/save`);
        toastMessage.value = "Product saved!";
        setTimeout(() => {
            toastMessage.value = null;
        }, 3000);
    } catch (error) {
        console.error("Error saving product:", error);
    }
};

const deleteProduct = (id) => {
    if (confirm("Are you sure you want to delete this product?")) {
        router.delete(route("products.destroy", id), {
            preserveState: false,
        });
    }
};

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
                console.error("Error adding product to cart:", errors);
            },
        },
    );
};
</script>

<style scoped>
/* Container styling */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

/* Header styling */
h1 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1rem;
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

/* Grid Styling */
.grid {
    display: grid;
    gap: 1.5rem;
}

.grid-cols-1 {
    grid-template-columns: 1fr;
}

.md\\:grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
}

.lg\\:grid-cols-4 {
    grid-template-columns: repeat(4, 1fr);
}

/* Card styling */
.bg-white {
    background-color: white;
}

.p-4 {
    padding: 1rem;
}

.rounded-lg {
    border-radius: 0.5rem;
}

.shadow-lg {
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
}

/* Image styling */
.img {
    max-width: 100%;
    height: auto;
}

.object-cover {
    object-fit: cover;
}

.mb-4 {
    margin-bottom: 1rem;
}
</style>
