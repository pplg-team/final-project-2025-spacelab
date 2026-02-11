import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
  server: {
    host: '0.0.0.0',
    hmr: {
      host: '192.168.137.1'
    }
  },

  plugins: [
    laravel({
      input: [
        "resources/css/app.css",
        "resources/js/app.js",

        "resources/js/staff/student-index.js",
        "resources/js/staff/teacher-index.js",
        "resources/js/staff/room-index.js",
        "resources/css/home-animations.css",
        "resources/js/home-interactions.js",
        "resources/js/staff/staff-index.js",
      ],
      refresh: true,
    }),
  ],
});
