<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    <div class="flex items-start">
        <div class="w-full">
            <div x-data="{ activeTab: 'tab1' }" class="pt-8">
                <div class="flex justify-start ml-10 relative  ">
                    <div class="absolute inset-x-0 bottom-0 border-b-[1px] border-gray-400 "></div>
                    <div class="text-gray-400 flex items-center cursor-pointer px-6 pb-2 z-10 "
                        x-on:click="activeTab = 'tab1'" wire:click="$set('activeTab', 'tab1')"
                        :class="{ ' border-b-2 border-b-primary-400': activeTab === 'tab1' }">
                        <x-filament::icon icon="fas-table"
                            x-bind:class="{ 'text-primary-400': activeTab === 'tab1' }"
                            class="h-5 w-5 text-gray-400 dark:text-gray-400" />
                        <span :class="{ 'font-semibold text-primary-400': activeTab === 'tab1' }"
                            class="max-w-xl ml-2">Tabel</span>
                    </div>
                    <div class="text-gray-400 flex items-center cursor-pointer px-6 pb-2 z-10 "
                        x-on:click="activeTab = 'tab2'" wire:click="$set('activeTab', 'tab2')"
                        :class="{ 'relative border-b-2 border-b-primary-400': activeTab === 'tab2' }">
                        <x-filament::icon icon="fas-chart-simple"
                            x-bind:class="{ 'text-primary-300': activeTab === 'tab2' }"
                            class="h-5 w-5 text-gray-400 dark:text-gray-400" />
                        <span :class="{ 'font-semibold text-primary-400': activeTab === 'tab2' }"
                            class="max-w-xl ml-2">Grafik</span>
                    </div>

                </div>

                <div class="ml-10 space-y-2 mt-4">
                    <livewire:stat.filter-form :stat="$record" :activeTab="$activeTab" />
                </div>
            </div>
        </div>
    </div>

    @if (count($relationManagers = $this->getRelationManagers()))
        <x-filament-panels::resources.relation-managers :active-manager="$this->activeRelationManager" :managers="$relationManagers" :owner-record="$record"
            :page-class="static::class" />
    @endif


</x-filament-panels::page>
