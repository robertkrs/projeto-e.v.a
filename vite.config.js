import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js',
                'resources/sass/app.scss', // ← esta linha precisa existir
                'resources/js/painel/produto.js',
            ],
            refresh: true,
        }),
    ],
});
