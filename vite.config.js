import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'
import { visualizer } from 'rollup-plugin-visualizer'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        vue(),
        visualizer({
            filename: 'stats.html',
            open: false,
            gzipSize: true,
        }),
    ],
    test: {
        environment: 'jsdom',
        globals: true,
    }
})
