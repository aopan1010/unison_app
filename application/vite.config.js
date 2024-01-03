import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

import VitePluginBrowserSync from 'vite-plugin-browser-sync';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        VitePluginBrowserSync({
            mode: 'snippet'
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
    server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
        port: 3000,
    }
});