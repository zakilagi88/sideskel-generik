<?php

namespace App\Filament\Widgets;

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

class PendudukApexBarChart extends ApexChartWidget

{

    /**
     * Chart Id
     *
     * @var string
     */
    // protected int | string | array $columnSpan = 'full';
    protected static ?string $loadingIndicator = 'Loading...';
    protected static string $chartId = 'pendudukApexBarChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Kelompok Umur Penduduk Berdasarkan Jenis Kelamin';

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

        // Inisialisasi array untuk menyimpan data berdasarkan kelompok umur dan jenis kelamin
        $dataPekerjaan = [];

        // Iterasi melalui data penduduk
        foreach ($penduduk as $individu) {
            $jenisPekerjaan = $individu->pekerjaan;
            $dataPekerjaan[] = $jenisPekerjaan ?? 0;
        }
        $pekerjaanCount = [];

        // Loop melalui data pekerjaan
        foreach ($dataPekerjaan as $pekerjaan) {
            // Ambil nama pekerjaan
            $namaPekerjaan = $pekerjaan->value; // Sesuaikan dengan metode yang benar

            // Tambahkan jumlah pekerjaan ke dalam array $pekerjaanCount
            if (isset($pekerjaanCount[$namaPekerjaan])) {
                $pekerjaanCount[$namaPekerjaan]++;
            } else {
                $pekerjaanCount[$namaPekerjaan] = 1;
            }
        }

        // Urutkan array $pekerjaanCount berdasarkan jumlah pekerjaan
        arsort($pekerjaanCount);

        // Ambil 10 pekerjaan teratas
        $top10Pekerjaan = array_slice($pekerjaanCount, 0, 11);

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

        $pekerjaan = $this->getData();

        $top10Pekerjaan = array_keys($pekerjaan[0]);
        $jumlahPekerjaan = array_values($pekerjaan[0]);


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
                        'name' => 'Pekerjaan',
                        'data' => ($jumlahPekerjaan),
                    ],

                ],
                'plotOptions' => [
                    'bar' => [
                        'borderRadius' => 2,
                    ],
                ],
                'xaxis' => [
                    'categories' => $top10Pekerjaan,
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


    // protected static function getOptions(): RawJs
    // {
    //     $UmurData = static::getData();


    //     $dataLakiLaki = [];
    //     $dataPerempuan = [];

    //     foreach ($UmurData as $kelompokUmur => $data) {
    //         $jumlahLakiLaki = $data["LAKI-LAKI"];
    //         $dataLakiLaki[] = $jumlahLakiLaki;
    //     }

    //     foreach ($UmurData as $kelompokUmur => $data) {
    //         $jumlahPerempuan = -1 * $data["PEREMPUAN"];
    //         $dataPerempuan[] = $jumlahPerempuan;
    //     }

    //     $kategoriUmur = array_keys($UmurData);

    //     // dd($dataLakiLaki);

    //     $dataLakiLakiJSON = json_encode($dataLakiLaki);
    //     $dataPerempuanJSON = json_encode($dataPerempuan);
    //     $kategoriUmurJSON = json_encode($kategoriUmur);

    //     $data = Trend::model(Penduduk::class)
    //         ->between(
    //             start: now()->startOfMonth(),
    //             end: now()->endOfMonth(),
    //         )
    //         ->perDay()
    //         ->count();
    //     return RawJs::make(<<<JS
    //     {
    //         chart: {
    //             type: 'bar',
    //             height: 500,
    //             toolbar: {
    //                 show: false
    //             },
    //             sparkline: {
    //                 enabled: false
    //             },
    //             stacked: true,

    //         },
    //         series: [
    //             {
    //                 name: 'Laki-Laki',
    //                 data: $dataLakiLakiJSON,
    //             },
    //             {
    //                 name: 'Perempuan',
    //                 data: $dataPerempuanJSON,
    //             }
    //         ],
    //         plotOptions: {
    //             bar: {
    //                 borderRadius: 2,
    //             },
    //         },
    //         xaxis: {
    //             categories: $kategoriUmurJSON,
    //             labels: {
    //                 style: {
    //                     fontWeight: 400,
    //                     fontFamily: 'inherit'
    //                 },
    //                 formatter: function (value) {
    //                     return value + ' Tahun';
    //                 },
    //             },
    //         },
    //         yaxis: {
    //             min: -15,
    //             max: 15,
    //             title: {
    //                 text: 'Jumlah Penduduk'
    //             },
    //             labels: {
    //                 style: {
    //                     fontWeight: 400,
    //                     fontFamily: 'inherit'
    //                 },
    //             },
    //         },
    //         fill: {
    //             type: 'gradient',
    //             gradient: {
    //                 shade: 'dark',
    //                 type: 'vertical',
    //                 shadeIntensity: 0.5,
    //                 inverseColors: true,
    //                 opacityFrom: 1,
    //                 opacityTo: 1,
    //                 stops: [0, 100],
    //             },
    //         },
    //         plotOptions: {
    //             bar: {
    //                 borderRadius: 3,
    //                 horizontal: true,
    //                 barHeight: '80%',
    //             },
    //         },
    //         grid: {
    //             show: true,
    //             borderColor: '#f3f3f3',
    //         },
    //         tooltip: {
    //             enabled: true
    //         },
    //         dataLabels: {
    //             enabled: false,
    //         },
    //         colors: ['#008FFB', '#FF4560'],
    //     }

    //     JS);
    // }

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
