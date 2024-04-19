/** @type {import('tailwindcss').Config} */
import preset from "./vendor/filament/support/tailwind.config.preset";

module.exports = {
    presets: [preset],

    content: [
        "./app/Filament/**/*.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./resources/views/filament/**/*.blade.php",
        "./resources/views/components/**/*.blade.php",
        "./resources/views/livewire/**/*.blade.php",
        "./vendor/bezhansalleh/filament-exceptions/resources/views/**/*.blade.php", // Language Switch Views
        "./vendor/filament/**/*.blade.php",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
