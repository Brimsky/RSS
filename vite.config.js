import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', // Add more entry points as needed
                'resources/css/app.css' // If you have a CSS file
            ],
            refresh: true, // Enables automatic refresh on file changes
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    build: {
        outDir: 'public/build', // Ensure Vite builds go to the correct directory
    },
});
