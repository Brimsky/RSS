import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
        }),
        vue(),
    ],
    resolve: {
        alias: {
            ziggy: path.resolve(__dirname, 'vendor/tightenco/ziggy/dist/vue.m'),
        },
    },
});
