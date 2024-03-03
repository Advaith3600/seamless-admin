import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            publicDirectory: 'src',
            buildDirectory: 'assets',
            input: [
                'src/resources/assets/scss/app.scss',
                'src/resources/assets/js/app.js',
                'src/resources/assets/js/type-index.js',
                'src/resources/assets/js/foreign-selection.js',
            ],
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
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
            '@': '/src/resources/assets'
        },
    },
});
