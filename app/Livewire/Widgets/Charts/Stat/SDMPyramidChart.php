<?php

namespace App\Livewire\Widgets\Charts\Stat;

use App\Models\Penduduk\PendudukView;
use App\Models\StatSDM;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Reactive;

class SDMPyramidChart extends ApexChartWidget

{
    use InteractsWithPageFilters;

    #[Reactive]
    public ?array $chartData;

    public ?Model $record = null;

    public ?string $filter = 'horizontal';

    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'sdm-pyramid-chart';
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

        array_walk_recursive($key['pr'], function (&$item) {
            $item = -1 * $item;
        });

        $options =
            [
                'chart' => [
                    'type' => 'bar',
                    'height' => 1024,
                    'toolbar' => [
                        'show' => true
                    ],
                    'stacked' => true,
                ],
                'series' => [
                    [
                        'name' => 'Laki-laki',
                        'data' => $key['lk'],
                    ],
                    [
                        'name' => 'Perempuan',
                        'data' => $key['pr'],
                    ],
                ],
                'colors' => ['#6366f1', '#ec4899'],
                'plotOptions' => [
                    'bar' => [
                        'dataLabels' => [
                            'position' => 'top', // top, center, bottom
                        ],
                        'borderRadius' => 2,
                        'barHeight' => '100%',
                        'horizontal' => true,
                    ],
                ],
                'xaxis' => [
                    'categories' => $key[$this->chartKey()],
                ],
                'yaxis' => [
                    'min' => -25,
                    'max' => 25,
                    'title' => [
                        'text' => 'Umur'
                    ],
                ],
                'stroke' => [
                    'width' => 0.5,
                    'colors' => ['#fff'],
                ],
            ];

        return $options;
    }

    protected function extraJsOptions(): ?RawJs
    {

        return RawJs::make(
            <<<'JS'
        {
                tooltip: {
                    shared: false,
                    x: {
                        formatter: function (value) {
                            return value + " Tahun"
                        }
                    },
                    y: {
                        formatter: function (value) {
                            return Math.abs(value) + " Orang"
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                            return Math.abs(Math.round(val))
                        },
                    style: {
                        colors: ['#fff'],
                        fontSize: '8px',
                    }
                },
                xaxis: {
                    labels: {
                        style: {
                            colors: '#9ca3af',
                            fontWeight: 600,
                        },
                        formatter: function (val) {
                            return Math.abs(Math.round(val)) + " Orang"
                        }
                    },
                }
                
            }
        JS
        );
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
