<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 w-full">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink :href="route('cart')" :active="route().current('cart')">
                                    Cart
                                </NavLink>
                                <!-- Products Page Link -->
                                <NavLink :href="route('products.index')" :active="route().current('products.index')">
                                    Products
                                </NavLink>
                            </div>
                        </div>

                        <!-- Settings Dropdown (Centered) -->
                        <div class="flex items-center justify-center w-full sm:justify-end">
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}
                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('cart')" :active="route().current('cart')">
                            Cart
                        </ResponsiveNavLink>
                        <!-- Responsive Products Page Link -->
                        <ResponsiveNavLink :href="route('products.index')" :active="route().current('products.index')">
                            Products
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Product List Content -->
            <div class="container">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-bold">
                        <span class="inline-block align-middle mr-2">
                            <!-- You can add an SVG icon here if you want -->
                        </span>
                        Product List
                    </h1>
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
                            <td class="font-semibold">{{ product.name }}</td>
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
    </div>
</template>

<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

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
        async addToCart(product) {
            await this.$inertia.post('/cart/add', { product_id: product.id });
            alert('Product added to cart!');
        },
    },
};
</script>

<style scoped>
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
