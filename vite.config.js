import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
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
