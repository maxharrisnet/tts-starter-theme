import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import legacy from '@vitejs/plugin-legacy';
import path from 'path';

export default defineConfig({
  plugins: [
    tailwindcss(),
    legacy({ targets: ['defaults', 'not IE 11'] }),
  ],
  build: {
    outDir: 'assets/dist',
    rollupOptions: {
      input: {
        main:  path.resolve( __dirname, 'assets/src/main.css' ),
        app:   path.resolve( __dirname, 'assets/src/app.js' ),
        admin: path.resolve( __dirname, 'assets/src/admin.js' ),
      },
      output: {
        entryFileNames: '[name].js',
        chunkFileNames: '[name].js',
        assetFileNames: '[name].[ext]',
      },
    },
    manifest: true,
  },
  server: {
    proxy: {
      '/': 'http://localhost:8888',
    },
  },
} );
