<?php

namespace App\Livewire\Widgets\Chart;

use App\Enum\Penduduk\JenisKelamin;
use App\Livewire\Widgets\Table\PendidikanTable;
use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;

class PendidikanChart extends ApexChartWidget

{
    /**
     * Chart Id
     *
     * @var string
     */
    // protected int | string | array $columnSpan = 'full';
    protected static ?string $loadingIndicator = 'Loading...';
    protected static string $chartId = 'PendidikanChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Penduduk Berdasarkan Pendidikan';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */

    protected function getData(): array
    {
        $datatabel = app(PendidikanTable::class)->data();
        $pendidikanSeries = [];
        $totalSeries = [];
        $items = $datatabel['data'];
        $pendidikanSeries = array_column($items, 'PENDIDIKAN');
        $totalSeries = array_column($items, 'TOTAL');

        // Resulting arrays
        $result = [
            'pendidikan' => $pendidikanSeries,
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

        $pendidikan = self::getData();
        $jenisPendidikan = $pendidikan['pendidikan'];
        $jumlahPendidikan = $pendidikan['total'];

        // $optionsPie =
        //     [
        //         'series' => $jumlahPendidikan,
        //         'labels' => $jenisPendidikan,
        //         'chart' => [
        //             'type' => 'donut',

        //         ],
        //         'plotOptions' => [
        //             'pie' => [
        //                 'expandOnClick' => true,
        //                 'size' => 200,
        //                 'donut' => [
        //                     'labels' => [
        //                         'show' => true,
        //                     ],
        //                     'size' => '65%',
        //                 ],
        //             ],
        //         ],
        //     ];
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
                        'name' => 'Pendidikan',
                        'data' => ($jumlahPendidikan),
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
                    'categories' => $jenisPendidikan,
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

        return $options;
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
