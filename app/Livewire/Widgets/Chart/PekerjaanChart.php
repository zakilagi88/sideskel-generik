<?php

namespace App\Livewire\Widgets\Chart;

use App\Enum\Penduduk\JenisKelamin;
use App\Livewire\Widgets\Table\PekerjaanTable;
use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;

class PekerjaanChart extends ApexChartWidget

{
    /**
     * Chart Id
     *
     * @var string
     */
    // protected int | string | array $columnSpan = 'full';
    protected static ?string $loadingIndicator = 'Loading...';
    protected static string $chartId = 'PekerjaanChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Penduduk Berdasarkan Pekerjaan';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    public $pekerjaanSeries = [];
    public $totalSeries = [];

    protected function getData(): array
    {
        $datatabel = app(PekerjaanTable::class)->data();
        $items = $datatabel['data'];
        $pekerjaanSeries = array_column($items, 'PEKERJAAN');
        $totalSeries = array_column($items, 'TOTAL');

        // Resulting arrays
        $result = [
            'pekerjaan' => $pekerjaanSeries,
            'total' => $totalSeries,
        ];

        return $result;
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    protected function getOptions(): array
    {

        $pekerjaan = self::getData();
        $jenisPekerjaan = $pekerjaan['pekerjaan'];
        $jumlahPekerjaan = $pekerjaan['total'];

        $optionsPie =
            [
                'series' => $jumlahPekerjaan,
                'labels' => $jenisPekerjaan,
                'chart' => [
                    'type' => 'donut',

                ],
                'plotOptions' => [
                    'pie' => [
                        'expandOnClick' => true,
                        'size' => 200,
                        'donut' => [
                            'labels' => [
                                'show' => true,
                            ],
                            'size' => '65%',
                        ],
                    ],
                ],
            ];
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
                    // 'stacked' => true,

                ],
                'series' => [
                    [
                        'name' => 'Pekerjaan',
                        'data' => ($jumlahPekerjaan),
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
                        'borderRadius' => 2,
                    ],
                ],
                'xaxis' => [
                    'categories' => $jenisPekerjaan,
                    'labels' => [
                        'style' => [
                            'fontWeight' => 400,
                            'fontFamily' => 'inherit'
                        ],
                        // 'formatter' => new RawJs('function (value) { return value + " Tahun"; }'),

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
                    'type' => 'gradient',
                    'gradient' => [
                        'shade' => 'dark',
                        'type' => 'vertical',
                        'shadeIntensity' => 0.5,
                        'inverseColors' => true,
                        'opacityFrom' => 1,
                        'opacityTo' => 1,
                        'stops' => [0, 100],
                    ],
                ],
                'plotOptions' => [
                    'bar' => [
                        'borderRadius' => 3,
                        'horizontal' => false,
                        'barHeight' => '100%',
                        'distributed' => true,
                    ],
                ],

                'dataLabels' => [
                    'enabled' => false,
                    'textAnchor' => 'start',
                    'style' => [
                        'colors' => ['#fff']
                    ],
                    // 'formatter' => new RawJs('function (val, opt) {
                    //     return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                    //   }'),

                ],
                'grid' => [
                    'show' => true,
                    'borderColor' => '#f3f3f3',
                ],
                'tooltip' => [
                    'enabled' => true
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

        return $optionsPie;
    }



    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }
}
