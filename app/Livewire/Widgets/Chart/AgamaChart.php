<?php

namespace App\Livewire\Widgets\Chart;

use App\Enum\Penduduk\JenisKelamin;
use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\RawJs;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AgamaChart extends ApexChartWidget

{

    /**
     * Chart Id
     *
     * @var string
     */
    // protected int | string | array $columnSpan = 'full';
    protected static ?string $loadingIndicator = 'Loading...';
    protected static string $chartId = 'AgamaChart';

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
    // protected function getOptions(): array
    // {


    protected function getData(): array
    {
        $penduduk = Penduduk::all();

        $dataAgama = [];

        foreach ($penduduk as $individu) {
            $agama = $individu->agama;
            $dataAgama[] = $agama ?? 0;
        }
        $agamaCount = [];

        foreach ($dataAgama as $agama) {
            // Ambil nama pekerjaan
            $namaAgama = $agama->value; // Sesuaikan dengan metode yang benar

            // Tambahkan jumlah pekerjaan ke dalam array $agamaCount
            if (isset($agamaCount[$namaAgama])) {
                $agamaCount[$namaAgama]++;
            } else {
                $agamaCount[$namaAgama] = 1;
            }
        }

        // Urutkan array $agamaCount berdasarkan jumlah pekerjaan
        arsort($agamaCount);

        // Ambil 10 pekerjaan teratas
        $top10Pekerjaan = array_slice($agamaCount, 0, 11);

        // Output hasilnya
        return
            [$top10Pekerjaan];
    }




    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {

        $agama = $this->getData();

        $top10Agama = array_keys($agama[0]);
        $jumlahAgama = array_values($agama[0]);


        $options =
            [
                'chart' => [
                    'type' => 'bar',
                    'height' => 500,
                    'toolbar' => [
                        'show' => false
                    ],
                    'sparkline' => [
                        'enabled' => false
                    ],
                    // 'stacked' => true,

                ],
                'series' => [
                    [
                        'name' => 'Agama',
                        'data' => ($jumlahAgama),
                    ],

                ],
                'plotOptions' => [
                    'bar' => [
                        'borderRadius' => 2,
                    ],
                ],
                'xaxis' => [
                    'categories' => $top10Agama,
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
                        'horizontal' => true,
                        'barHeight' => '40%',
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
                    // sediakan 10 warna gradasi
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
