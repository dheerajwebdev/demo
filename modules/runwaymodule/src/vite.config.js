import ViteRestart from 'vite-plugin-restart'
import mkcert from 'vite-plugin-mkcert'
import { nodeResolve } from '@rollup/plugin-node-resolve'
import * as path from 'path'
import vue from '@vitejs/plugin-vue'

export default ({ command }) => ({
  base: command === 'serve' ? '' : '/dist/',
  publicDir: 'non-existent-path',
  build: {
    manifest: true,
    outDir: './web/dist/',
    rollupOptions: {
      input: {
        frontend: './web/src/frontend/main.js',
        backend: './web/src/backend/main.js',
      },
    },
  },
  server: {
    host: '0.0.0.0',
    port: 3002,
    strictPort: true,
    https: true,
    hmr: {
      host: 'localhost',
      port: 3002,
      path: '/',
    },
  },
  resolve: {
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js',
    },
  },
  plugins: [
    vue({ customElement: true }),
    mkcert(),
    ViteRestart({
      reload: ['./templates/**/*'],
    }),
    nodeResolve({
      moduleDirectories: [path.resolve('./node_modules')],
    }),
  ],
})
