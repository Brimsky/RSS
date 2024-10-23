<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Вкладки -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8" aria-label="Tabs">
                            <button 
                                @click="setActiveTab('products')"
                                :class="['px-3 py-2 text-sm font-medium', activeTab === 'products' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700']"
                            >
                                Products
                            </button>
                            <button 
                                @click="setActiveTab('users')"
                                :class="['px-3 py-2 text-sm font-medium', activeTab === 'users' ? 'border-b-2 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:text-gray-700']"
                            >
                                Users
                            </button>
                        </nav>
                    </div>

                    <!-- Вкладка Products -->
                    <div v-if="activeTab === 'products'">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Products Management</h2>

                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="product in products" :key="product.id">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <img 
                                                v-if="product.image" 
                                                :src="product.image" 
                                                class="h-10 w-10 rounded-full object-cover"
                                                alt="Product image"
                                            />
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ product.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">${{ formatPrice(product.price) }}</td>
                                        <td class="px-6 py-4">{{ product.description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button 
                                                @click="openProductModal(product)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                @click="handleDelete('product', product.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Вкладка Users -->
                    <div v-if="activeTab === 'users'">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold">Users Management</h2>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="user in users" :key="user.id">
                                        <td class="px-6 py-4 whitespace-nowrap">{{ user.name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ user.email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ user.role }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <button 
                                                @click="openUserModal(user)"
                                                class="text-indigo-600 hover:text-indigo-900 mr-4"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                v-if="user.role !== 'admin'"
                                                @click="handleDelete('user', user.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Модальное окно для продуктов -->
                    <div v-if="showProductModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg">
            <h3 class="text-lg font-bold mb-4">
                {{ editingProduct.id ? 'Edit Product' : 'Add New Product' }}
            </h3>
            <form @submit.prevent="saveProduct">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input 
                            type="text" 
                            v-model="editingProduct.name"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <input 
                            type="number" 
                            v-model="editingProduct.price"
                            required
                            step="0.01"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea 
                            v-model="editingProduct.description"
                            required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Image URL</label>
                        <input 
                            type="text" 
                            v-model="editingProduct.image"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button 
                        type="button"
                        @click="closeProductModal"
                        class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        class="btn-primary"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

                    <!-- Модальное окно для пользователей -->
                    <div v-if="showUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                        <div class="bg-white p-6 rounded-lg w-full max-w-lg">
                            <h3 class="text-lg font-bold mb-4">Edit User</h3>
                            <form @submit.prevent="saveUser">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <input 
                                            type="text" 
                                            v-model="editingUser.name"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input 
                                            type="email" 
                                            v-model="editingUser.email"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Role</label>
                                        <select 
                                            v-model="editingUser.role"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        >
                                            <option value="buyer">buyer</option>
                                            <option value="seller">seller</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mt-6 flex justify-end space-x-3">
                                    <button 
                                        type="button"
                                        @click="closeUserModal"
                                        class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50"
                                    >
                                        Cancel
                                    </button>
                                    <button 
                                        type="submit"
                                        class="btn-primary"
                                    >
                                        Save
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    products: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    }
});

const activeTab = ref('products');
const showProductModal = ref(false);
const showUserModal = ref(false);
const editingProduct = ref({
    id: null,
    name: '',
    price: '',
    description: '',
    image: ''
});
const editingUser = ref({
    id: null,
    name: '',
    email: '',
    role: ''
});

const setActiveTab = (tab) => {
    activeTab.value = tab;
    localStorage.setItem('adminActiveTab', tab);
};

const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};

const openProductModal = (product = null) => {
    if (product) {
        editingProduct.value = { ...product }; 
    } else {
        editingProduct.value = {
            id: null,
            name: '',
            price: '',
            description: '',
            image: ''
        };
    }
    showProductModal.value = true;
};

const openUserModal = (user) => {
    editingUser.value = { ...user };
    showUserModal.value = true;
};

const closeProductModal = () => {
    showProductModal.value = false;
    editingProduct.value = {
        id: null,
        name: '',
        price: '',
        description: '',
        image: ''
    };
};

const closeUserModal = () => {
    showUserModal.value = false;
    editingUser.value = {
        id: null,
        name: '',
        email: '',
        role: ''
    };
};

 const saveProduct = async () => {
    try {
        await router.put(
            route('admin.products.update', editingProduct.value.id),
            editingProduct.value,
            {
                preserveScroll: true,
                onSuccess: () => {
                    closeProductModal();
                    router.visit(route('admin.index')); 
                },
                onError: (errors) => {
                    console.error('Validation errors:', errors);
                    alert('Error updating product: ' + Object.values(errors).join('\n'));
                }
            }
        );
    } catch (error) {
        console.error('Error saving product:', error);
        alert('Error saving product: ' + error.message);
    }
};

const saveUser = async () => {
    try {
        await router.put(
            route('admin.users.update', editingUser.value.id), 
            editingUser.value,
            {
                preserveScroll: true,
                onSuccess: () => {
                    closeUserModal();
                
                },
                onError: (errors) => {
                    console.error('Validation errors:', errors);
                    alert('Error updating user: ' + Object.values(errors).join('\n'));
                }
            }
        );
    } catch (error) {
        console.error('Error saving user:', error);
        alert('Error saving user: ' + error.message);
    }
};


watch(() => editingUser.value.role, (newRole, oldRole) => {
    console.log('Role changed from', oldRole, 'to', newRole);
});

const handleDelete = async (type, id) => {
    const confirmMessage = type === 'product' ? 'этот продукт' : 'этого пользователя';
    
    if (confirm(`Вы уверены, что хотите удалить ${confirmMessage}?`)) {
        try {
            const routeName = type === 'product' ? 'admin.products.destroy' : 'admin.users.destroy';
            
            await router.delete(route(routeName, id), {
                preserveScroll: true,
                onSuccess: () => {
                    // Reload the page using Inertia
                    router.visit(route('admin.index'), {
                        preserveScroll: true
                    });
                },
                onError: (errors) => {
                    console.error('Delete error:', errors);
                    alert(`Error deleting ${type}: ` + Object.values(errors).join('\n'));
                }
            });
        } catch (error) {
            console.error('Delete error:', error);
            alert('Произошла ошибка: ' + (error.response?.data?.message || error.message));
        }
    }
}


</script>

<style scoped>
.btn-primary {
    @apply inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 active:bg-indigo-600 disabled:opacity-25 transition;
}


.modal-overlay {
    @apply fixed inset-0 bg-black bg-opacity-50;
}

.modal-content {
    @apply bg-white p-6 rounded-lg max-w-lg mx-auto mt-20;
}

.form-input {
    @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500;
}

.form-label {
    @apply block text-sm font-medium text-gray-700;
}

.btn-secondary {
    @apply px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50;
}
</style>