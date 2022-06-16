import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
import eslintPlugin from "vite-plugin-eslint";
import vue from "@vitejs/plugin-vue";
import { resolve } from "path";

/* if you're using React */
// import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [
    /* react(), // if you're using React */
    symfonyPlugin(),
    vue(),
    eslintPlugin(),
  ],
  resolve: {
    alias: {
      "@": resolve(__dirname, "./assets/"),
      "~bootstrap": "bootstrap",
    },
  },
  root: ".",
  base: "/build/",
  build: {
    manifest: true,
    emptyOutDir: true,
    assetsDir: "",
    outDir: "./public/build",
    rollupOptions: {
      input: {
        app: "./assets/app.js",
      },
    },
  },
});
