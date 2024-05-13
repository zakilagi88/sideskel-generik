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

class SDMBarChart extends ApexChartWidget

{
    use InteractsWithPageFilters;

    #[Reactive]
    public ?array $chartData;

    public Model | int | string | null $record;

    public ?string $filter = 'horizontal';

    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'sdm-bar-chart';
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
        return 'Grafik Penduduk Menurut ' . ucFirst($this->chartKey);
    }

    #[Computed()]
    public function chartKey(): string
    {
        return $this->record instanceof StatSDM ? $this->record->key : $this->record->nama;
    }

    protected function getData(array $chartData): array
    {
        return [
            $this->chartKey => array_column($chartData, $this->chartKey),
            'lk' => array_column($chartData, 'laki_laki'),
            'pr' => array_column($chartData, 'perempuan'),
            'total' => array_column($chartData, 'total'),
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'horizontal' => 'Horizontal Bar',
            'vertical' => 'Vertical Bar'
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

        $options =
            [
                'chart' => [
                    'type' => 'bar',
                    'height' => 600,
                    'toolbar' => [
                        'show' => true
                    ],
                    'sparkline' => [
                        'enabled' => false
                    ],
                ],
                'series' => [
                    [
                        'name' => 'Total Penduduk',
                        'data' => $key['total'],
                    ],
                    [
                        'name' => 'Laki-laki',
                        'data' => $key['lk'],
                    ],
                    [
                        'name' => 'Perempuan',
                        'data' => $key['pr'],
                    ],
                ],
                'plotOptions' => [
                    'pie' => [
                        'expandOnClick' => true,
                        'size' => 100,
                        'donut' => [
                            'size' => '65%',
                        ],
                    ],
                    'bar' => [
                        'dataLabels' => [
                            'position' => 'top', // top, center, bottom
                        ],
                        'horizontal' => $this->filter === 'horizontal' ? true : false,
                        'borderRadius' => 2,
                        'columnWidth' => '90%',
                        'endingShape' => 'flat',
                    ],
                ],
                'xaxis' => [
                    'categories' => $key[$this->chartKey],
                    'labels' => [
                        'rotate' => -90,
                        'offsetY' => 2,
                        'style' => [
                            'fontWeight' => 400,
                            'fontFamily' => 'inherit'
                        ],
                    ],
                ],
                'yaxis' => [

                    'title' => [
                        'text' => 'Jumlah Penduduk'
                    ],
                    'labels' => [
                        'style' => [
                            'fontWeight' => 400,
                            'fontFamily' => 'inherit'
                        ],
                    ],
                ],
                'fill' => [
                    'gradient' => [
                        'shade' => 'light',
                        'type' => 'horizontal',
                        'shadeIntensity' => 0.5,
                        'gradientToColors' => [
                            '#008FFB',
                            '#00E396',
                            '#FEB019',
                            '#FF4560',
                            '#775DD0',
                            '#3F51B5',
                            '#546E7A',
                            '#D4526E',
                            '#8D5B4C',
                            '#F86624',
                        ],
                        'inverseColors' => false,
                        'opacityFrom' => 1,
                        'opacityTo' => 1,
                        'stops' => [0, 50, 100],
                    ],
                ],
                'dataLabels' => [
                    'enabled' => false,
                    'offsetX' => -1,
                    'style' => [
                        'colors' => ['#fff']
                    ],

                ],
                'legend' => [
                    'show' => true,
                    'position' => 'right',
                    'horizontalAlign' => 'left',
                    'floating' => false,
                    'fontSize' => '14px',
                    'fontWeight' => 400,
                    'onItemClick' => [
                        'toggleDataSeries' => true
                    ],
                    'onItemHover' => [
                        'highlightDataSeries' => true
                    ],
                ],
                'tooltip' => [
                    'shared' => false,
                ],
                'colors' => [
                    '#008FFB',
                    '#00E396',
                    '#FEB019',
                    '#FF4560',
                    '#775DD0',
                    '#3F51B5',
                    '#546E7A',
                    '#D4526E',
                    '#8D5B4C',
                    '#F86624',
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
