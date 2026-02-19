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
                'resources/js/formulario-informe.js',
                'resources/js/alertas.js',
                'resources/js/informe-listado.js',
                'resources/css/alerts.css',
                'resources/css/revision.css',
                'resources/css/dashboard.css',
                'resources/css/paciente-informes.css',
                'resources/css/paciente-login.css',
                'resources/css/paginas.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
}); 