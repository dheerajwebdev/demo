import ViteRestart from 'vite-plugin-restart'
import mkcert from'vite-plugin-mkcert'

export default ({ command }) => ({
  base: command === 'serve' ? '' : '/dist/',
  publicDir: 'non-existent-path',
  build: {
    manifest: true,
    outDir: './web/dist/',
    rollupOptions: {
      input: {
        app: './src/main.js',
      },
    },
  },
  server: {
    host: 'localhost',
    port: 3002,
    strictPort: true,
    https: true,
  },
  plugins: [
    mkcert(),
    ViteRestart({
      reload: ['./templates/**/*'],
    }),
  ],
})