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
 
        @filamentStyles
        @vite('resources/css/app.css')
    </head>
 
    <body class="antialiased">
        <x-filament-panels::page>

<x-filament::section
    icon="heroicon-m-user"
    icon-size="sm"
    collapsible

>
    <x-slot name="heading">
        User details
    </x-slot>
 
<x-filament::input.wrapper>
    <x-filament::input
        type="text"
        wire:model="name"
    />
</x-filament::input.wrapper>
 
<x-filament::input.wrapper>
    <x-filament::input.select wire:model="status">
        <option value="draft">Draft</option>
        <option value="reviewing">Reviewing</option>
        <option value="published">Published</option>
    </x-filament::input.select>
</x-filament::input.wrapper>

</x-filament::section>
 
<x-filament::section
    icon="heroicon-m-user"
    icon-size="md"
>
    <x-slot name="heading">
        User details
    </x-slot>
 <x-filament::tabs label="Content tabs">
    <x-filament::tabs.item>
        Tab 1
    </x-filament::tabs.item>
 
    <x-filament::tabs.item>
        Tab 2
    </x-filament::tabs.item>
 
    <x-filament::tabs.item>
        Tab 2
    </x-filament::tabs.item>
</x-filament::tabs>

   </x-filament::section>
   </x-filament-panels::page>

 
        <footer class="relative bottom-0 left-0 z-20 w-full p-4 bg-white border-t border-gray-200 shadow md:flex md:items-center md:justify-between md:p-6 dark:bg-gray-800 dark:border-gray-600">
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">© 2023
        <a href="#" class="hover:underline">Kelurahan Kuripan™</a>. All Rights Reserved.
    </span>
</footer>

        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>