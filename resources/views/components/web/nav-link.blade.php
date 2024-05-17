{{-- Path: resources/views/components/web/nav-link.blade.php --}}
@props(['items'])
@if (isset($items['sub_link_type']))
    @if ($items['sub_link_type'] == 'static')
        <a href="{{ route($items['sub_link_name']) }}" wire:navigate class="block pl-4">{{ $items['sub_name'] }}</a>
    @else
        <a href="{{ route($items['sub_link_name'], ['record' => $items['sub_link_options']]) }}" class="block pl-4"
            wire:navigate>{{ $items['sub_name'] }}</a>
    @endif
@else
    @if ($items['link_type'] == 'static')
        <a href="{{ route($items['link_name']) }}" wire:navigate class="block">{{ $items['name'] }}</a>
    @else
        @if (Route::has($items['link_name']))
            <a href="#" class="block" wire:navigate>{{ $items['name'] }}</a>
        @else
            <a href="{{ route($items['link_name'], ['record' => $items['link_options']]) }}" class="block"
                wire:navigate>{{ $items['name'] }}</a>
        @endif
    @endif
@endif
