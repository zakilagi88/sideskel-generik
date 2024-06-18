@props(['stat'])

@php
    $isActive = Route::is('filament.panel.statistik.pages.' . $stat->stat_slug);
@endphp

<a class="px-4 py-2 text-sm transition-all duration-300 ease-in-out text-gray-600
            block bg-primary-300 rounded-lg shadow-md mb-1
            {{ $isActive ? 'border-l-2 transform translate-x-2 border-blue-500 font-bold' : 'hover:bg-gray-300 transform -translate-x-2' }}"
    wire:navigate.hover href="{{ route('filament.panel.statistik.pages.' . $stat->stat_slug) }}"
    @click.prevent="{{ $stat->id }}">
    {{ $stat->stat_heading }}
</a>
