import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [react()],
  define: {
    'process.env.NODE_ENV': JSON.stringify('production'),
    'process.env': JSON.stringify({ NODE_ENV: 'production' }),
  },
  build: {
    emptyOutDir: true,
    outDir: 'assets/react-islands/dist',
    lib: {
      entry: 'assets/react-islands/src/main.jsx',
      name: 'CodruFestivalReactIslands',
      formats: ['iife'],
      fileName: () => 'react-islands.js',
      cssFileName: 'react-islands',
    },
    rollupOptions: {
      output: {
        banner: 'var process = window.process || { env: { NODE_ENV: "production" } }; window.process = process;',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name === 'style.css') {
            return 'react-islands.css';
          }

          return '[name].[ext]';
        },
      },
    },
  },
});
