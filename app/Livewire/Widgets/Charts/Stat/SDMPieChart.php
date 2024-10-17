<?php

namespace App\Livewire\Widgets\Charts\Stat;

use App\Models\Penduduk\PendudukView;
use App\Models\StatSDM;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;

class SDMPieChart extends ApexChartWidget

{
    use InteractsWithPageFilters;

    #[Reactive]
    public $chartData;

    public Model | int | string | null $record;

    public ?string $filter = 'horizontal';


    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'sdm-pie-chart';
    protected static bool $deferLoading = true;
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Penduduk';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */



    protected function getHeading(): ?string
    {
        return 'Grafik Penduduk Menurut ' . ucFirst($this->chartKey());
    }

    public function chartKey(): string
    {
        return $this->record instanceof StatSDM ? $this->record->key : $this->record->nama;
    }

    protected function getData(array $chartData): array
    {
        return [
            $this->chartKey() => array_column($chartData, $this->chartKey()),
            'lk' => array_column($chartData, 'laki_laki'),
            'pr' => array_column($chartData, 'perempuan'),
            'total' => array_column($chartData, 'total'),
        ];
    }
    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    protected function getOptions(): array
    {
        if (!$this->readyToLoad) {
            return [
                'loading..'
            ];
        }

        $key = $this->getData($this->chartData);

        $options = [
            'chart' => [
                'type' => 'pie',
                'height' => 350,
            ],
            'series' => $key['total'],
            'labels' => $key[$this->chartKey()],
            'plotOptions' => [
                'pie' => [
                    'startAngle' => -90,
                    'endAngle' => 270,
                    'expandOnClick' => true,
                    'size' => 100,
                    'donut' => [
                        'size' => '65%',
                    ],
                ],
            ],
            'colors' => [
                '#545454',  // Dark Gray
                '#FF66C4',  // Light Pink
                '#FF3131',  // Bright Red
                '#00BF63',  // Green
                '#004AAD',  // Blue
                '#849D12',  // Olive Green
                '#FF914D',  // Orange
                '#E8DD78',  // Light Yellow
                '#364A64',  // Navy
                '#754D35',  // Brown
                '#D6C6AF',  // Light Beige
                '#0097B2',  // Cyan
                '#C1FF72',  // Light Green
                '#9D34B3',  // Purple
                '#769F9E',  // Teal Gray
                '#9F7679',  // Muted Pink
                '#A6000F',  // Dark Red
                '#D97706',  // Amber
                '#14B8A6',  // Teal
                '#6366F1',  // Blue-Violet
                '#4B5563',  // Cool Gray
                '#FACC15',  // Bright Yellow
                '#0EA5E9',  // Light Blue
                '#F53855',  // Pink
                '#FFB4A2',  // Light Pink
            ],

            'dataLabels' => [
                'enabled' => true,
                'offset' => 0,
            ],
            'fill' => [
                'type' => 'gradient',
            ],
            'title' => [
                'text' => 'Total Penduduk',
                'align' => 'center',
                'margin' => 20,
                'style' => [
                    'fontSize' => '25px',
                ],
            ],
            'legend' => [
                'labels' => [
                    'colors' => '#9ca3af',
                    'fontWeight' => 600,
                ],
            ],
            'responsive' => [
                [
                    'breakpoint' => 480,
                    'options' => [
                        'chart' => [
                            'width' => 200,
                        ],
                        'legend' => [
                            'position' => 'bottom',
                        ],
                    ],
                ],
            ],
        ];

        return $options;
    }

    public function updateOptions(): void
    {
        if ($this->options !== $this->getOptions()) {

            $this->options = $this->getOptions();

            $this
                ->dispatch('updateOptions', options: $this->options)
                ->self();

            if (!$this->dropdownOpen) {
                $this
                    ->dispatch('updateOptions', options: $this->options)
                    ->self();
            }
        }
    }
}
