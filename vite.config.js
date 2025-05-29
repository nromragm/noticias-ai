import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    base: 'https://noticias-ai-production.up.railway.app/',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/darkmode-boton.js'],
            refresh: true,
        }),
    ],
});
