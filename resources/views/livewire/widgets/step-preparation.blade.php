<div class="w-full md:w-1/4 text-center" id="{{ $step['id'] }}">
    <div class="mb-4 flex flex-col items-center">
        <!-- Progress bar -->
        <div class="flex items-center justify-center w-full">
            <div class="w-full bg-gray-200 rounded-full">
                <div
                    class="w-{{ $step['completed'] ? 'full' : '0' }} bg-{{ $step['completed'] ? 'primary-400' : 'gray-200' }} h-2 rounded">
                </div>
            </div>
        </div>

        <!-- Icon -->
        <div
            class="w-12 h-12 mx-auto mt-4 {{ $step['completed'] ? 'bg-primary-400' : 'bg-white border-2 border-gray-200' }} rounded-full text-lg text-white flex items-center justify-center">
            <span class="text-center {{ $step['completed'] ? 'text-white' : 'text-primary-400' }}">
                @if ($step['completed'])
                    <x-filament::icon-button alias="{{ $step['icon'] }}" icon="fas-check" href="{{ $step['href'] }}"
                        wire:click="nextStep({{ $step['id'] }})" tag="a"
                        class="h-5 w-5 text-white dark:text-gray-400" />
                @else
                    <x-filament::icon-button alias="{{ $step['icon'] }}" icon="{{ $step['icon'] }}" tag="a"
                        href="{{ $step['href'] }}" wire:click="nextStep({{ $step['id'] }})"
                        class="h-5 w-5 text-{{ $step['completed'] ? 'white' : 'gray-400' }}" />
                @endif
            </span>
        </div>
    </div>

    <!-- Step label -->
    <div class="text-xs">{{ $step['label'] }}</div>

    <!-- Step description -->
    @if ($step['description'])
        <div class="text-xs text-gray-500 mt-1">{{ $step['description'] }}</div>
    @endif
</div>
