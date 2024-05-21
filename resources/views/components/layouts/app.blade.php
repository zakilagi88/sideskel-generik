<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    <title>{{ config('app.name') }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    {{-- <link rel="stylesheet" href="https://cdn.tailgrids.com/tailgrids-fallback.css" /> --}}
    <link rel="stylesheet" href="assets('css/all.css')">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />

    @filamentStyles
    @vite('resources/css/app.css')
    @vite('resources/css/filament/admin/theme.css')

</head>

<body class="antialiased bg-secondary-100 min-h-screen">

    <x-header />

    <main class="container my-10">

        {{ $slot }}

    </main>

    @livewire('components.footer')

    @filamentScripts
    @vite('resources/js/app.js')

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>
</body>

</html>
