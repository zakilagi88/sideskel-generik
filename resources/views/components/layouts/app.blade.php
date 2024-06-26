<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    @filamentStyles
    @vite('resources/css/filament/admin/theme.css')

</head>

<body class="antialiased bg-secondary-100 min-h-screen">

    <x-layouts.partials.header />

    <main class="container home my-10 mx-auto">

        {{ $slot }}

    </main>

    <x-layouts.partials.footer />

    @filamentScripts
    @vite('resources/js/app.js')

    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            offset: 200,
            delay: 100,
            duration: 2000,
        });
    </script>
</body>

</html>
