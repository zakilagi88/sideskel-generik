<?php

namespace App\Filament\Widgets;

use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class PendudukChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penduduk';

    protected static string $color = 'success';

    protected static ?string $maxHeight = '900px';


    protected function getData(): array
    {
        $penduduk = Penduduk::all();

        // Inisialisasi array untuk menyimpan data berdasarkan kelompok umur dan jenis kelamin
        $umurData = [];

        // Iterasi melalui data penduduk
        foreach ($penduduk as $individu) {
            $tanggalLahir = Carbon::createFromFormat('Y-m-d', $individu->tanggal_lahir);
            $usia = $tanggalLahir->diffInYears(Carbon::now());

            // Mengelompokkan penduduk berdasarkan kelompok umur
            if ($usia >= 0 && $usia <= 4) {
                $kelompokUmur = '0-4';
            } elseif ($usia >= 5 && $usia <= 9) {
                $kelompokUmur = '5-9';
            } elseif ($usia >= 10 && $usia <= 14) {
                $kelompokUmur = '10-14';
            } elseif ($usia >= 15 && $usia <= 19) {
                $kelompokUmur = '15-19';
            } elseif ($usia >= 20 && $usia <= 24) {
                $kelompokUmur = '20-24';
            } elseif ($usia >= 25 && $usia <= 29) {
                $kelompokUmur = '25-29';
            } elseif ($usia >= 30 && $usia <= 34) {
                $kelompokUmur = '30-34';
            } elseif ($usia >= 35 && $usia <= 39) {
                $kelompokUmur = '35-39';
            } elseif ($usia >= 40 && $usia <= 44) {
                $kelompokUmur = '40-44';
            } elseif ($usia >= 45 && $usia <= 49) {
                $kelompokUmur = '45-49';
            } elseif ($usia >= 50 && $usia <= 54) {
                $kelompokUmur = '50-54';
            } elseif ($usia >= 55 && $usia <= 59) {
                $kelompokUmur = '55-59';
            } elseif ($usia >= 60 && $usia <= 64) {
                $kelompokUmur = '60-64';
            } elseif ($usia >= 65 && $usia <= 69) {
                $kelompokUmur = '65-69';
            } elseif ($usia >= 70 && $usia <= 74) {
                $kelompokUmur = '70-74';
            } else {
                $kelompokUmur = '75+';
            }

            // Menginisialisasi array jika belum ada
            if (!isset($umurData[$kelompokUmur])) {
                $umurData[$kelompokUmur] = ['LAKI-LAKI' => 0, 'PEREMPUAN' => 0];
            }

            // Menghitung jumlah penduduk berdasarkan jenis kelamin
            $jenisKelamin = $individu->jenis_kelamin->value;
            $umurData[$kelompokUmur][$jenisKelamin]++;
        }

        uksort($umurData, function ($a, $b) {
            $aParts = explode('-', $a);
            $bParts = explode('-', $b);

            $aMinAge = (int)$aParts[0];
            $bMinAge = (int)$bParts[0];

            return $aMinAge - $bMinAge;
        });

        $dataLakiLaki = [];
        $dataPerempuan = [];
        $kategoriUmur = array_keys($umurData);

        foreach ($umurData as $kelompokUmur => $data) {
            $jumlahLakiLaki = $data["LAKI-LAKI"];
            $dataLakiLaki[] = $jumlahLakiLaki;
        }

        foreach ($umurData as $kelompokUmur => $data) {
            $jumlahPerempuan = -1 * $data["PEREMPUAN"];
            $dataPerempuan[] = $jumlahPerempuan;
        }


        return [
            'datasets' => [
                [
                    'label' => 'Laki-laki',
                    'data' => $dataLakiLaki,
                    'barWidth' => '20px', // default: '10px
                    'barPercentage' => 1,
                    'backgroundColor' => ' rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Perempuan',
                    'data' => $dataPerempuan,
                    'barPercentage' => 1,
                    'barWidth' => '20px', // default: '10px
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $kategoriUmur,
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            aspectRatio: 1.5,
            indexAxis: 'y',
            scales: {
            
                x: {
                    min: -15,
                    max: 15,
                    stacked: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return Math.abs(value);
                        }
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Kelompok Umur',
                    },
                    stacked: true,
                    
                },
            },
            plugins: {
                
                
                tooltip: {
                    yAlign: 'bottom',
                    titleAlign: 'center',
                    callbacks: {
                        label: function(context) {
                
                            return context.dataset.label + ': ' + Math.abs(context.raw);

                        },
                    },
                },
            
                legend: {
                    position: 'right',
                },  
            },
        }
        JS);
    }


    protected function getType(): string
    {
        return 'bar';
    }

    public function getDescription(): ?string
    {
        return 'Jumlah Penduduk berdasarkan Jenis Kelamin dan Kelompok Umur';
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
