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
        "./resources/views/components/**/*.blade.php",
        "./vendor/filament/**/*.blade.php",
        "./vendor/filament/**/*.js",
        "./vendor/filament/**/*.vue",
        "./vendor/awcodes/filament-table-repeater/resources/**/*.blade.php",
    ],
    theme: {
        container: {
            margin: "1rem",
            padding: "1rem",
        },

        extend: {
            colors: {
                primary: {
                    50: "rgba(var(--primary-50), <alpha-value>)",
                    100: "rgba(var(--primary-100), <alpha-value>)",
                    200: "rgba(var(--primary-200), <alpha-value>)",
                    300: "rgba(var(--primary-300), <alpha-value>)",
                    400: "rgba(var(--primary-400), <alpha-value>)",
                    500: "rgba(var(--primary-500), <alpha-value>)",
                    600: "rgba(var(--primary-600), <alpha-value>)",
                    700: "rgba(var(--primary-700), <alpha-value>)",
                    800: "rgba(var(--primary-800), <alpha-value>)",
                    900: "rgba(var(--primary-900), <alpha-value>)",
                },
                secondary: {
                    50: "rgba(var(--secondary-50), <alpha-value>)",
                    100: "rgba(var(--secondary-100), <alpha-value>)",
                    200: "rgba(var(--secondary-200), <alpha-value>)",
                    300: "rgba(var(--secondary-300), <alpha-value>)",
                    400: "rgba(var(--secondary-400), <alpha-value>)",
                    500: "rgba(var(--secondary-500), <alpha-value>)",
                    600: "rgba(var(--secondary-600), <alpha-value>)",
                    700: "rgba(var(--secondary-700), <alpha-value>)",
                    800: "rgba(var(--secondary-800), <alpha-value>)",
                    900: "rgba(var(--secondary-900), <alpha-value>)",
                },
            },
        },
    },
};
