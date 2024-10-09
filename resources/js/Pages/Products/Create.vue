<template>
    <div class="container">
        <h1 class="page-title">Create Product</h1>
        <form @submit.prevent="submit" class="product-form">
            <div class="form-group">
                <label for="name">Name:</label>
                <input v-model="form.name" type="text" id="name" class="form-control" placeholder="Enter product name" required />
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea v-model="form.description" id="description" class="form-control" placeholder="Enter product description" required></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input v-model="form.price" type="number" id="price" class="form-control" placeholder="Enter product price" required />
            </div>
            <button type="submit" class="btn btn-submit">Create Product</button>
        </form>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
    data() {
        return {
            form: {
                name: '',
                description: '',
                price: '',
            },
        };
    },
    methods: {
        submit() {
            Inertia.post('/products', this.form);
        },
    },
};
</script>

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

.form-group {
    margin-bottom: 20px; /* Adjusted margin for better spacing */
}

label {
    font-weight: 600;
    margin-bottom: 5px;
    display: block; /* Ensure labels take full width */
}

.form-control {
    padding: 10px;
    border: 1px solid #ced4da;
    border-radius: 5px;
    font-size: 16px;
    transition: border-color 0.3s;
    width: 100%; /* Ensure full width */
}

.form-control:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

.btn-submit {
    padding: 12px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-submit:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}
</style>
