<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// Получаем доступ к странице через composable
const page = usePage();

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    user: {
        type: Object,
        required: true,
    },
});

const avatarUrl = ref(null);

// Метод для получения URL аватара
const getAvatarUrl = () => {
    if (props.user.media && props.user.media.length > 0) {
        console.log('Media found:', props.user.media[0]);
        // Use the correct path to access the image
        return '/storage/' + props.user.media[0].id + '/' + props.user.media[0].file_name;
    }
    console.log('No media found');
    return null;
};

onMounted(() => {
    console.log('User data:', props.user);
    avatarUrl.value = getAvatarUrl();
    console.log('Avatar URL:', avatarUrl.value);
    console.log('User role:', page.props.auth.user.role);
});
</script>

<template>
    <Head title="Profile"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Profile</h2>
        </template>

        <!-- Используем page.props вместо $page.props -->
        <div v-if="page.props.auth.user.role === 'admin'" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h3 class="text-lg font-medium text-gray-900">Admin Controls</h3>
            <div class="mt-4">
                <Link :href="route('admin.index')" class="btn-primary">
                    Go to Admin Panel
                </Link>
            </div>
        </div>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <!-- Avatar display section -->
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="flex items-center space-x-4">
                        <div v-if="getAvatarUrl()" class="flex-shrink-0">
                            <img v-if="avatarUrl" :src="avatarUrl" alt="User Avatar" class="h-24 w-24 rounded-full object-cover" />
                        </div>
                        <div v-else class="flex-shrink-0">
                            <div class="h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ user.name }}</h3>
                            <p class="text-sm text-gray-500">{{ user.email }}</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        :user="user"
                        class="max-w-xl"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>