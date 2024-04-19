<?php

namespace App\Livewire\Widgets\Charts;

use App\Models\Penduduk\PendudukAgama;
use App\Models\Penduduk\PendudukView;
use App\Models\Stat;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class StatChart extends ApexChartWidget

{
    use InteractsWithPageFilters;

    public Stat $stat;

    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'agama-chart';
    protected static bool $deferLoading = true;
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Penduduk Berdasarkan Agama';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    protected function getData(): array
    {

        $items = static::getTableEloquentQuery()->get()->toArray();

        return [
            $this->stat->key => array_column($items, $this->stat->key),
            'lk' => array_column($items, 'laki_laki'),
            'pr' => array_column($items, 'perempuan'),
            'total' => array_column($items, 'total'),
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

        $key = $this->getData();

        switch ($this->filterFormData['type']) {
            case 'bar':
                $typeChart = 'bar';
                break;
            case 'pie':
                $typeChart = 'pie';
                break;
            default:
                $typeChart = 'bar';
                break;
        }

        $options =
            [
                'chart' => [
                    'type' => $typeChart,
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
                        'horizontal' => $this->filterFormData['is_horizontal'] ?? false,
                        'borderRadius' => 3,
                        'columnWidth' => '80%',
                        'endingShape' => 'rounded',
                    ],
                ],
                'xaxis' => [
                    'categories' => $key[$this->stat->key],
                    'labels' => [
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
                        'gradientToColors' => ['#FFC107', '#FFC107', '#FFC107'],
                        'inverseColors' => false,
                        'opacityFrom' => 1,
                        'opacityTo' => 1,
                        'stops' => [0, 50, 100],
                    ],
                ],
                'dataLabels' => [
                    'enabled' => true,
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

        $pieOptions = [
            'chart' => [
                'type' => 'pie',
                'height' => 350,
            ],
            'series' => $key['total'],
            'labels' => $key[$this->stat->key],
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

    protected function getFormSchema(): array
    {
        return [
            Select::make('type')
                ->label('Type')
                ->placeholder('Type')
                ->live()
                ->default('bar')
                ->options([
                    'bar' => 'Bar',
                    'pie' => 'Pie',
                ])
                ->afterStateUpdated(function () {
                    $this->updateOptions();
                }),
            Checkbox::make('is_horizontal')
                ->label('Horizontal Bar')
                ->live()
                ->default(true)
                ->afterStateUpdated(function () {
                    $this->updateOptions();
                }),
        ];
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

    public function getTableEloquentQuery(): Builder
    {
        $activeFilters = $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;

        return PendudukView::getView(key: $this->stat->key, wilayahId: $activeFilters)
            ->when(
                isset($this->filters['key']) && $this->filters['key'] !== [],
                function (Builder $query) {
                    $query->whereIn($this->stat->key, $this->filters['key']);
                }
            );
    }
}