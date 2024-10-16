<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-100">
            <!-- Product List Content -->
            <div class="container">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">Product List</h1>
                    <a href="/products/create" class="btn btn-primary">Add New Product</a>
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
                            <!-- Link to the product details page -->
                            <td class="font-semibold">
                                <a :href="`/products/${product.id}`" class="text-blue-600 hover:underline">
                                    {{ product.name }}
                                </a>
                            </td>
                            <td>${{ parseFloat(product.price).toFixed(2) }}</td>
                            <td>{{ product.description }}</td>
                            <td class="text-right">
                                <a :href="`/products/${product.id}/edit`" class="btn btn-warning mr-2">Edit</a>
                                <button @click="deleteProduct(product.id)" class="btn btn-danger mr-2">Delete</button>
                                <button @click="addToCart(product)" class="btn btn-primary">Add to Cart</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Handle dropdown and other UI elements
const showingNavigationDropdown = ref(false);

</script>

<script>
export default {
    props: {
        products: Array,
    },
    methods: {
        // Delete product from the list
        async deleteProduct(id) {
            if (confirm("Are you sure you want to delete this product?")) {
                await this.$inertia.delete(`/products/${id}`);
            }
        },
        // Add product to the cart
        async addToCart(product) {
            await this.$inertia.post('/cart/add', { product_id: product.id });
            alert('Product added to cart!');
        }
    }
}
</script>

<style scoped>
/* Container styling */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

h1 {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
}

/* Button styling */
.btn {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 0.25rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
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

.inline-flex {
    border: none;
    outline: none;
}

button {
    border: none;
    outline: none;
    background: none;
}
</style>
