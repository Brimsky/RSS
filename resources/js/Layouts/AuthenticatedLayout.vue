<template>
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 flex-shrink-0">
            <!-- Primary Navigation Menu -->
            <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
                <div class="flex justify-between h-14 sm:h-16">
                    <div class="flex items-center">
                        <!-- Logo/Home Link -->
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="group">
                                <h1 class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-500 bg-clip-text text-transparent group-hover:from-purple-500 group-hover:to-blue-400 transition-all duration-300">
                                    RSS
                                </h1>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-4 sm:space-x-8 sm:-my-px sm:ml-6 sm:ml-10 sm:flex">
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                            >
                                Dashboard
                            </NavLink>
                            <NavLink
                                v-if="$page.props.auth.user.role === 'buyer'"
                                href="/"
                            >
                                Browse Products
                            </NavLink>
                            <NavLink
                                v-if="$page.props.auth.user.role === 'seller'"
                                :href="route('products.index')"
                                :active="route().current('products.index')"
                            >
                                Products
                            </NavLink>
                            <NavLink
                                v-if="$page.props.auth.user.role === 'buyer'"
                                :href="route('cart')"
                                :active="route().current('cart')"
                                class="relative"
                            >
                                Cart
                                <span
                                    v-if="cartCount > 0"
                                    class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center"
                                >
                                    {{ cartCount }}
                                </span>
                            </NavLink>
                            <NavLink
                                :href="route('chat.index')"
                                :active="route().current('chat.index')"
                                >
                                Chat
                                </NavLink
                            >
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            <div class="flex items-center">
                                                <!-- Avatar Container -->
                                                <div
                                                    class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center mr-2 overflow-hidden"
                                                >
                                                    <img
                                                        v-if="avatarUrl"
                                                        :src="avatarUrl"
                                                        :alt="user.name"
                                                        class="h-8 w-8 rounded-full object-cover"
                                                    />
                                                    <span
                                                        v-else
                                                        class="text-purple-600 font-medium"
                                                    >
                                                        {{
                                                            user.name
                                                                .charAt(0)
                                                                .toUpperCase()
                                                        }}
                                                    </span>
                                                </div>
                                                {{ user.name }}
                                            </div>

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
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
                                    <DropdownLink :href="route('profile.edit')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="w-full text-left"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Hamburger -->
                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
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
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden"
            >
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="flex items-center">
                            <!-- Avatar in mobile menu -->
                            <div
                                class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center mr-3 overflow-hidden"
                            >
                                <img
                                    v-if="avatarUrl"
                                    :src="avatarUrl"
                                    :alt="user.name"
                                    class="h-10 w-10 rounded-full object-cover"
                                />
                                <span
                                    v-else
                                    class="text-purple-600 font-medium text-lg"
                                >
                                    {{ user.name.charAt(0).toUpperCase() }}
                                </span>
                            </div>
                            <div>
                                <div
                                    class="font-medium text-base text-gray-800"
                                >
                                    {{ user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">
                                    {{ user.email }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1 bg-gray-50 relative">
            <div class="absolute inset-0 overflow-auto">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import Chat from "@/Pages/Chat.vue";

// Reactive State
const showingNavigationDropdown = ref(false);
const page = usePage();
const user = ref(page.props.auth.user);
const avatarUrl = ref(null);
const cartCount = ref(0); // You'll need to implement this based on your cart system

// Methods
const getAvatarUrl = () => {
    if (user.value?.media?.length > 0) {
        return `/storage/${user.value.media[0].id}/${user.value.media[0].file_name}`;
    }
    return null;
};

// Lifecycle Hooks
onMounted(() => {
    avatarUrl.value = getAvatarUrl();
    // You might want to fetch the cart count here
    // Example: fetchCartCount();
});
</script>

<style scoped>
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
}

.transition-all {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}
</style>
