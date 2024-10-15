<template>
    <div>
        <h3 class="font-semibold text-lg text-gray-800">Update Avatar</h3>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="avatar" class="block text-sm font-medium text-gray-700">Choose an Avatar</label>
                <input
                    type="file"
                    id="avatar"
                    @change="handleFileChange"
                    class="mt-1 block w-full text-sm text-gray-500
                    file:py-2 file:px-4
                    file:border file:border-gray-300
                    file:rounded-lg file:text-sm file:font-semibold
                    hover:file:bg-gray-100"
                    accept="image/*"
                />
            </div>

            <div v-if="avatarUrl" class="mb-4">
                <img :src="avatarUrl" alt="Avatar Preview" class="h-32 w-32 object-cover rounded-full" />
            </div>

            <button
                type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
            >
                Upload Avatar
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';

const avatarUrl = ref(null);
const file = ref(null);

const handleFileChange = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile) {
        file.value = selectedFile;
        avatarUrl.value = URL.createObjectURL(selectedFile);
    }
};

const submit = () => {
    const formData = new FormData();
    formData.append('avatar', file.value);

    // Замените этот URL на ваш серверный API endpoint
    fetch('/profile/avatar', { // Измените URL на соответствующий маршрут
        method: 'POST',
        body: formData,
    })
        .then(response => {
            if (!response.ok) throw new Error('Failed to upload avatar');
            // Логика для обновления страницы или уведомления пользователя
        })
        .catch(error => {
            console.error(error);
        });
};
</script>