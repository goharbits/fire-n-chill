import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.scss',
                'resources/css/fonts.css',
                'resources/css/style.css',
                'resources/js/app.js',
                'resources/js/custom.js',
                'resources/js/jquery-ui.min.js',
            ],
            refresh: true,
        }),
    ],
});
