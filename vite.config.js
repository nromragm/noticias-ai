import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    //base: process.env.ASSET_URL || '/',
    base: '/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/darkmode-boton.js', 'resources/js/darkmode-auto.js'],
            refresh: true,
        }),
    ],
});
