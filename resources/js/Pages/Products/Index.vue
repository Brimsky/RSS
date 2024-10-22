<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100 py-6">
            <div class="container">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold">Product List</h1>
                    <template v-if="$page.props.auth.user.role === 'seller'">
                        <Link
                            :href="route('products.create')"
                            class="btn btn-primary"
                        >
                            Add New Product
                        </Link>
                    </template>
                </div>

                <!-- Product Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products" :key="product.id">
                            <td class="font-semibold">
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="text-blue-600 hover:underline"
                                    @click="registerClick(product.id)"
                                >
                                    {{ product.name }}
                                </Link>
                            </td>
                            <td>${{ parseFloat(product.price).toFixed(2) }}</td>
                            <td>{{ product.description }}</td>
                            <td class="text-right">
                                <template
                                    v-if="
                                        $page.props.auth.user.role === 'seller'
                                    "
                                >
                                    <Link
                                        :href="
                                            route('products.edit', product.id)
                                        "
                                        class="btn btn-warning mr-2"
                                    >
                                        Edit
                                    </Link>
                                    <button
                                        @click="deleteProduct(product.id)"
                                        class="btn btn-danger mr-2"
                                    >
                                        Delete
                                    </button>
                                </template>
                                <button
                                    @click="addToCart(product)"
                                    class="btn btn-primary"
                                >
                                    Add to Cart
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
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

/* Table styling */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1rem;
}

.table th,
.table td {
    padding: 0.75rem;
    vertical-align: top;
    border-top: 1px solid #e2e8f0;
}

.table th {
    font-weight: 600;
    text-align: left;
    background-color: #f7fafc;
}

.table tr:hover {
    background-color: #f7fafc;
}

/* Layout styling */
.flex {
    display: flex;
}

.justify-between {
    justify-content: space-between;
}

.items-center {
    align-items: center;
}

.mt-4 {
    margin-top: 1rem;
}

.text-right {
    text-align: right;
}

.font-semibold {
    font-weight: 600;
}

.text-blue-600 {
    color: #4299e1;
}

.text-blue-600:hover {
    text-decoration: underline;
}
</style>
