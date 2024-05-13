{{-- Path: resources/views/components/web/nav-sublink.blade.php --}}
@props('items')

@if ($items['link_type'] == 'static')
    <a href="{{ route($items['link_name']) }}" wire:navigate class="block pl-4">{{ $items['name'] }}
    </a>
@else
    <a href="{{ route($items['link_name'], ['record' => $items['link_options']]) }}" class="block pl-4" wire:navigate>
        {{ $items['name'] }}
    </a>
@endif
