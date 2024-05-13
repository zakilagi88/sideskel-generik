<div class="space-y-2">
    <div class="pb-[0.2rem]">
        {{ $this->form }}
    </div>

    {{-- <div x-show="activeTab === 'tab1'">
        @livewire('widgets.tables.stat-sdm-table', ['filters' => $this->filters, 'stat' => $this->stat])
    </div>
    <div x-show="activeTab === 'tab2'">
        @if ($this->filters['chart_type'] == 'bar')
            @livewire('widgets.charts.stat.bar-chart', ['items' => $this->getChartData(), 'key' => $this->stat->key])
        @else
            @livewire('widgets.charts.stat.pie-chart', ['items' => $this->getChartData(), 'key' => $this->stat->key])
        @endif
    </div> --}}
</div>
