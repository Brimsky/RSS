<template>
    <div class="relative">
        <button
            @click="isOpen = !isOpen"
            class="bg-white p-2 rounded-md text-gray-700 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            <span class="sr-only">Open menu</span>
            <UserIcon class="h-6 w-6" aria-hidden="true" />
        </button>

        <div
            v-if="isOpen"
            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
        >
            <router-link
                to="/login"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            >
                Login
            </router-link>
            <router-link
                to="/register"
                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
            >
                Register
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { UserIcon } from "@heroicons/vue/outline";

const isOpen = ref(false);
</script>
