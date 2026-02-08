import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: {
                'app': 'resources/css/app.css',
                'app_js': 'resources/js/app.js',
                'principal': 'resources/css/principal.css',
                'principal_js': 'resources/js/principal.js',
                'nuevoinforme': 'resources/css/nuevoinforme.css',
                'header_footer': 'resources/css/header_footer.css',
                'login': 'resources/css/login.css',
                'formulario': 'resources/js/formulario.js',
                'alerts': 'resources/css/alerts.css',
                'revision': 'resources/css/revision.css'
            },
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: `assets/[name].js`,
                chunkFileNames: `assets/[name].js`,
                assetFileNames: `assets/[name].[ext]`
            }
        },
        // Desactivar el vaciado de la carpeta para manejarlo nosotros o dejar que Vite lo haga limpiamente
        emptyOutDir: true,
    }
});
