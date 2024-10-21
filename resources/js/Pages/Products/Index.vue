<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100">
            <div class="container">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Product List</h1>
                    <Link :href="route('products.create')" class="btn btn-primary">Add New Product</Link>
                </div>

                <table class="table mt-4">
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
                                <Link :href="route('products.show', product.id)" class="text-blue-600 hover:underline" @click="registerClick(product.id)">
                                    {{ product.name }}
                                </Link>
                            </td>
                            <td>${{ parseFloat(product.price).toFixed(2) }}</td>
                            <td>{{ product.description }}</td>
                            <td class="text-right">
                                <Link :href="route('products.edit', product.id)" class="btn btn-warning mr-2">Edit</Link>
                                <button @click="deleteProduct(product.id)" class="btn btn-danger mr-2">Delete</button>
                                <button @click="addToCart(product)" class="btn btn-primary">Add to Cart</button>
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
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Toast from '@/Components/Toast.vue';
import axios from 'axios';

const props = defineProps({
    products: Array,
});

const toastMessage = ref(null);

const registerClick = async (productId) => {
    try {
        await axios.post(`/products/${productId}/register-click`);
    } catch (error) {
        console.error('Error registering click:', error);
    }
};

const deleteProduct = (id) => {
    if (confirm('Are you sure you want to delete this product?')) {
        router.delete(route('products.destroy', id), {
            preserveState: false,
        });
    }
};

const addToCart = (product) => {
    router.post(route('cart.add'), {
        product_id: product.id,
        quantity: 1,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            toastMessage.value = 'Product added to cart!';
            setTimeout(() => {
                toastMessage.value = null;
            }, 3000);
        },
        onError: (errors) => {
            console.error('Error adding product to cart:', errors);
        },
    });
};
</script>


<style scoped>
/* Container styling */
.container {
    max-width: 1200px; /* Maximum width */
    margin: 0 auto; /* Center the container */
    padding: 2rem; /* Padding around container */
}

/* Header styling */
h1 {
    font-size: 2rem; /* Font size for header */
    font-weight: bold; /* Font weight */
    margin-bottom: 1rem; /* Space below the header */
    display: flex; /* Flex display for alignment */
    align-items: center; /* Center items vertically */
}

/* Button styling */
.btn {
    display: inline-block; /* Inline block for buttons */
    padding: 0.5rem 1rem; /* Padding for buttons */
    border-radius: 0.25rem; /* Rounded corners */
    font-weight: 600; /* Font weight */
    text-decoration: none; /* Remove underline */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s, transform 0.2s; /* Transition effects */
}

.btn-primary {
    background-color: #4299e1; /* Primary button color */
    color: white; /* Text color */
}

.btn-primary:hover {
    background-color: #3182ce; /* Darker color on hover */
    transform: translateY(-1px); /* Lift effect */
}

.btn-warning {
    background-color: #ed8936; /* Warning button color */
    color: white; /* Text color */
}

.btn-warning:hover {
    background-color: #dd6b20; /* Darker color on hover */
}

.btn-danger {
    background-color: #f56565; /* Danger button color */
    color: white; /* Text color */
}

.btn-danger:hover {
    background-color: #e53e3e; /* Darker color on hover */
}

/* Table styling */
.table {
    width: 100%; /* Full width */
    border-collapse: separate; /* Separate borders */
    border-spacing: 0; /* No spacing */
    margin-top: 1rem; /* Space above the table */
}

.table th,
.table td {
    padding: 0.75rem; /* Padding for cells */
    vertical-align: top; /* Align to the top */
    border-top: 1px solid #e2e8f0; /* Top border for cells */
}

.table th {
    font-weight: 600; /* Font weight for headers */
    text-align: left; /* Align text to the left */
    background-color: #f7fafc; /* Header background */
}

.table tr:hover {
    background-color: #f7fafc; /* Hover effect */
}

/* Layout styling */
.flex {
    display: flex; /* Flex display */
}

.justify-between {
    justify-content: space-between; /* Space between items */
}

.items-center {
    align-items: center; /* Align items to the center */
}

.mt-4 {
    margin-top: 1rem; /* Top margin */
}

.text-right {
    text-align: right; /* Right alignment */
}

.font-semibold {
    font-weight: 600; /* Font weight */
}

.text-blue-600 {
    color: #4299e1; /* Blue color */
}

.text-blue-600:hover {
    text-decoration: underline; /* Underline on hover */
}

.inline-flex {
    border: none; /* Remove border */
    outline: none; /* Remove outline */
}

button {
    border: none; /* Remove border */
    outline: none; /* Remove outline */
    background: none; /* Remove background */
}
</style>