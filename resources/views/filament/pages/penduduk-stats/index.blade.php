<x-filament-panels::page>

    <section class="pb-20 sm:pb-20">
        <div class=" flex flex-col sm:flex-row items-start space-y-4 sm:space-y-0 sm:space-x-12">

            <div class="w-full sm:w-3/4 flex flex-col space-y-4 grow">


                {{ $this->getFiltersForm() }}


                <x-filament-widgets::widgets :columns="$this->getColumns()" :data="[
                    ...property_exists($this, 'filters') ? ['filters' => $this->filters] : [],
                    ...$this->getWidgetData(),
                ]" :widgets="$this->getVisibleWidgets()" />

            </div>
        </div>
    </section>

</x-filament-panels::page>
