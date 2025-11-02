import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/modal.css',
                'resources/css/scroll-animations.css',
                'resources/js/app.js',
                'resources/js/echo.js',
                'resources/js/toastNotification.js',
                'resources/js/verification.js',
                'resources/js/favoritesManager.js',
                'resources/js/scroll-animations.js',
                'resources/js/admin-dashboard-charts.js'
            ],
            refresh: true,
        }),
    ],
});
