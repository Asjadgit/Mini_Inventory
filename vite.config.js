import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                compilerOptions: {
                    // Enable Options API
                    isCustomElement: tag => tag.startsWith('x-')
                }
            }
        }),
    ],
     resolve: {
        alias: {
            // Make sure to alias vue to the full build
            'vue': 'vue/dist/vue.esm-bundler.js',
        }
    }
});
