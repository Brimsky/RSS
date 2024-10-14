<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3'; // For the "Back to Products" button

// Form data using Inertia's useForm hook
const form = useForm({
    name: '',
    price: '',
    description: ''
});

// State to track whether the product was successfully added
const productAdded = ref(false);

// Method to handle form submission
const submitForm = async () => {
    await form.post('/products', {
        onSuccess: () => {
            // Show success message
            productAdded.value = true;
        },
        onError: () => {
            // Handle any error if needed
            productAdded.value = false;
        }
    });
};
</script>

<template>
    <div class="container">
        <h1 class="page-title">Add New Product</h1>

        <!-- Form for adding a new product -->
        <form @submit.prevent="submitForm">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    id="price"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                    v-model="form.description"
                    id="description"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    required
                ></textarea>
            </div>

            <!-- Submit button -->
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
            >
                Add Product
            </button>
        </form>

        <!-- Success notification and "Back to Products" button -->
        <div v-if="productAdded" class="mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">Product added successfully.</span>
            </div>

            <!-- Back to Products button -->
            <Link href="/products" class="btn btn-secondary mt-4">Back to Products</Link>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.page-title {
    text-align: center;
    margin-bottom: 20px;
    font-size: 28px;
    font-weight: bold;
    color: #333;
}

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    display: inline-block;
    text-decoration: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    display: inline-block;
    text-decoration: none;
}

.btn-secondary:hover {
    background-color: #5a6268;
}
</style>
