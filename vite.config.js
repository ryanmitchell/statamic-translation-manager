import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import statamic from '@statamic/cms/vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/addon.js',
            ],
            publicDirectory: 'resources/dist',
            hotFile: 'resources/dist/hot',
        }),
        statamic(),
    ],
    build: {
        rollupOptions: {
            external: [
                /^@statamic\/cms/,
            ],
            output: {
                entryFileNames: 'js/addon.js',
                assetFileNames: 'assets/[name][extname]',
            },
        },
    },
});
