<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3'; // Inertia form handling

// Define form data using Inertia's useForm hook and populate it with product props
const props = defineProps({
    product: Object
});

const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
});

// State to track whether the product was successfully updated
const productUpdated = ref(false);

// Method to handle form submission
const submitForm = async () => {
    await form.put(`/products/${props.product.id}`, {
        onSuccess: () => {
            // Show success message
            productUpdated.value = true;
        },
        onError: () => {
            // Handle any error if needed
            productUpdated.value = false;
        }
    });
};
</script>

<template>
    <div class="container">
        <h1 class="page-title">Edit Product</h1>

        <!-- Form for editing product -->
        <form @submit.prevent="submitForm" class="product-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    id="name"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter product name"
                    required
                />
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea
                    v-model="form.description"
                    id="description"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter product description"
                    required
                ></textarea>
            </div>

            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    id="price"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                    placeholder="Enter product price"
                    required
                />
            </div>

            <!-- Submit button -->
            <button
                type="submit"
                class="btn btn-primary"
                :disabled="form.processing"
            >
                Update Product
            </button>
        </form>

        <!-- Success notification and back to products button -->
        <div v-if="productUpdated" class="mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">Product updated successfully.</span>
            </div>

            <!-- Back to Products button -->
            <Link href="/products" class="btn btn-secondary mt-4">Back to Products</Link>
        </div>
    </div>
</template>

<style scoped>
.container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.page-title {
    text-align: center;
    margin-bottom: 20px;
    font-size: 24px;
    font-weight: bold;
    color: #333;
}

.product-form {
    display: flex;
    flex-direction: column;
}

.mb-4 {
    margin-bottom: 20px;
}

label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block;
}

.form-control {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s;
    width: 100%;
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.btn-primary {
    background-color: #007bff;
    color: white;
    padding: 12px;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
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
