@php
    $user = filament()
        ->auth()
        ->user();
@endphp

<x-filament-panels::page class="fi-dashboard-page">


    <div class="flex flex-col">
        <x-filament-widgets::widgets :columns="$this->getColumns()" :data="$this->getWidgetData()" :widgets="$this->getVisibleWidgets()" />
    </div>

    <x-filament::section aside>
        <x-slot name="heading">
            User details for {{ $user->name }}
        </x-slot>

        <x-slot name="description">
            This is all the information we hold about the user.
        </x-slot>

        Kenapa tidak bisa diakses?

    </x-filament::section>






</x-filament-panels::page>
