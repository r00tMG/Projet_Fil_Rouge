import { fileURLToPath, URL } from 'node:url'
import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig(({ command,mode }) => {
  const env = loadEnv(mode, process.cwd(), '');

  return {
    plugins: [
      vue()
    ],
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url)),
      },
    },
    define: {
      __VITE_PUSHER_APP_KEY__: JSON.stringify(env.VITE_PUSHER_APP_KEY),
      __VITE_PUSHER_APP_CLUSTER__: JSON.stringify(env.VITE_PUSHER_APP_CLUSTER),
    },
  }
});
