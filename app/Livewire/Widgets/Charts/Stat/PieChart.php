<?php

namespace App\Livewire\Widgets\Charts\Stat;

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
use Livewire\Attributes\Reactive;

class PieChart extends ApexChartWidget

{
    use InteractsWithPageFilters;

    #[Reactive]
    public $items, $key;


    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'pie-chart';
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
        return 'Grafik Penduduk Menurut ' . ucFirst($this->key);
    }

    protected function getData(array $items): array
    {
        return [
            $this->key => array_column($items, $this->key),
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

        $key = $this->getData($this->items);

        $options = [
            'chart' => [
                'type' => 'pie',
                'height' => 350,
            ],
            'series' => $key['total'],
            'labels' => $key[$this->key],
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

    // protected function getFormSchema(): array
    // {
    //     return [
    //         Select::make('type')
    //             ->label('Type')
    //             ->placeholder('Type')
    //             ->live()
    //             ->default('bar')
    //             ->options([
    //                 'bar' => 'Bar',
    //                 'pie' => 'Pie',
    //             ])
    //             ->afterStateUpdated(function () {
    //                 $this->updateOptions();
    //             }),
    //         Checkbox::make('is_horizontal')
    //             ->label('Horizontal Bar')
    //             ->live()
    //             ->default(true)
    //             ->afterStateUpdated(function () {
    //                 $this->updateOptions();
    //             }),
    //     ];
    // }

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