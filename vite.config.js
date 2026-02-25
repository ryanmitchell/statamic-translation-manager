import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/cp.js',
            ],
            publicDirectory: 'resources/dist',
            hotFile: 'resources/dist/hot',
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
        host: '0.0.0.0',
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        rollupOptions: {
            external: [
                /^@statamic\/cms/,
                'vue',
            ],
            output: {
                entryFileNames: 'js/cp.js',
                assetFileNames: 'assets/[name][extname]',
                globals: {
                    vue: 'Vue',
                },
            },
        },
    },
});
