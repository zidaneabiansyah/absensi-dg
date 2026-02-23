import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // Optimize build for production
        minify: true,
        // Enable source maps for debugging
        sourcemap: false,
        // Increase chunk size warning threshold
        chunkSizeWarningLimit: 500,
        // Optimize assets
        assetsInlineLimit: 4096,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});

