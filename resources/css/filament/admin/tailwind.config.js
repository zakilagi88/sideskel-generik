/** @type {import('tailwindcss').Config} */

import preset from "../../../../vendor/filament/support/tailwind.config.preset";

export default {
    presets: [preset],
    content: [
        "./app/Filament/**/*.php",
        "./app/Filament/***/**/*.php",
        "./app/livewire/**/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/views/filament/**/*.blade.php",
        "./resources/views/livewire/**/*.blade.php",
        "./vendor/bezhansalleh/filament-exceptions/resources/views/**/*.blade.php", // Language Switch Views
        "./vendor/filament/**/*.blade.php",
        "./vendor/filament/**/*.js",
        "./vendor/filament/**/*.vue",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/**/*.blade.php",
    ],
};
