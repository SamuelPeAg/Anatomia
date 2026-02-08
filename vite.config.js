import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/principal.css',
                'resources/js/principal.js',
                'resources/css/nuevoinforme.css',
                'resources/css/header_footer.css',
                'resources/css/login.css',
                'resources/js/formulario.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
