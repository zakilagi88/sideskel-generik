<!-- resources/views/livewire/system-preparation.blade.php -->
<x-filament-widgets::widget>
    <x-filament::section>

        <div @if ($pollingInterval = $this->getPollingInterval()) wire:poll.{{ $pollingInterval }} @endif class="w-full py-2">
            <div class="text-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Persiapan Sistem</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">Mulai konfigurasi awal sistem dengan langkah-langkah
                    berikut:</p>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-4" id="stepContainer">
                @foreach ($steps as $step)
                    <livewire:widgets.step-preparation :step="$step" :key="$step['id']" wire:poll.5s />
                @endforeach
            </div>
        </div>
        @if ($allStepsCompleted)
            <x-filament::button wire:click="completeStep({{ $totalSteps }})" color="success">
                Selesai
            </x-filament::button>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
