<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    avatar: null,
});

const update = () => {
    if (form.avatar) {
        // Проверка размера файла
        const maxSize = 5 * 1024 * 1024; // 5 MB
        if (form.avatar.size > maxSize) {
            console.error('File size exceeds limit:', form.avatar.size);
            form.setError('avatar', 'File size exceeds 5MB limit');
            return;
        }

        // Проверка типа файла
        const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(form.avatar.type)) {
            console.error('Invalid file type:', form.avatar.type);
            form.setError('avatar', 'Invalid file type. Please use JPEG, PNG or GIF');
            return;
        }

        console.log('Uploading avatar:', form.avatar.name);
        form.post(route('profile.update'), {
            preserveScroll: true,
            preserveState: true,
            headers: { 'Content-Type': 'multipart/form-data' },
            onSuccess: () => console.log('Profile updated successfully with new avatar'),
            onError: (errors) => console.error('Error updating profile:', errors),
        });
    } else {
        console.log('Updating profile without new avatar');
        form.patch(route('profile.update'), {
            preserveScroll: true,
            onSuccess: () => console.log('Profile updated successfully'),
            onError: (errors) => console.error('Error updating profile:', errors),
        });
    }
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        console.log('File selected:', file.name, 'Size:', file.size, 'Type:', file.type);
        form.avatar = file;
    } else {
        console.log('No file selected');
        form.avatar = null;
    }
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form @submit.prevent="update" class="mt-6 space-y-6">
            <div>
                <InputLabel for="name" value="Name" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <InputLabel for="avatar" value="Avatar" />
                <input
                    type="file"
                    id="avatar"
                    @change="handleFileChange"
                    accept="image/*"
                    class="mt-1 block w-full"
                />
                <InputError class="mt-2" :message="form.errors.avatar" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </Transition>
            </div>
        </form>
    </section>
</template>
