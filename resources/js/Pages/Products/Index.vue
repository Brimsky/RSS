<template>
    <div class="container">
        <h1 class="page-title">Product List</h1>
        <a href="/products/create" class="btn btn-primary">Add New Product</a>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.id">
                    <td>{{ product.name }}</td>
                    <td>{{ parseFloat(product.price).toFixed(2) }}</td> <!-- Fix applied here -->
                    <td>{{ product.description }}</td>
                    <td>
                        <a :href="`/products/${product.id}/edit`" class="btn btn-warning">Edit</a>
                        <button @click="deleteProduct(product.id)" class="btn btn-danger">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        products: Array,
    },
    methods: {
        async deleteProduct(id) {
            if (confirm("Are you sure you want to delete this product?")) {
                await this.$inertia.delete(`/products/${id}`);
            }
        },
    },
};
</script>

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
    margin-bottom: 20px;
    display: inline-block;
    text-decoration: none;
}

.btn-primary:hover {
    background-color: #0056b3;
    transform: translateY(-1px);
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #dee2e6;
}

.table th {
    background-color: #007bff;
    color: white;
    font-weight: bold;
}

.table tr:hover {
    background-color: #f1f1f1;
}

.btn-warning, .btn-danger {
    padding: 6px 12px;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.2s;
}

.btn-warning {
    background-color: #ffc107;
}

.btn-warning:hover {
    background-color: #e0a800;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>
