<?php

namespace App\Livewire\Widgets\Chart;

use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class PendudukChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penduduk berdasarkan Jenis Kelamin dan Kelompok Umur';

    protected static string $color = 'success';

    protected static ?string $maxHeight = '900px';

    public ?string $filter = 'Kelompok Umur';

    public function groupByGender($penduduk)
    {
        $totals = ['LAKI-LAKI' => [], 'PEREMPUAN' => []];

        foreach ($penduduk as $individu) {
            $jenisKelamin = $individu->jenis_kelamin->value;
            $kelompokUmur = $individu->kelompok_umur;
            $total = $individu->total;

            if (!isset($totals[$jenisKelamin][$kelompokUmur])) {
                $totals[$jenisKelamin][$kelompokUmur] = 0;
            }

            $totals[$jenisKelamin][$kelompokUmur] += $total;
        }

        return ($totals);
    }

    public function prepareData($totals, $categories)
    {
        $preparedData = ['LAKI-LAKI' => [], 'PEREMPUAN' => []];

        foreach (['LAKI-LAKI', 'PEREMPUAN'] as $gender) {
            foreach ($categories as $category) {
                $preparedData[$gender][$category] = $totals[$gender][$category] ?? 0;
            }
        }

        $preparedData['UMUR'] = $categories;
        $preparedData['LAKI-LAKI'] = array_values($preparedData['LAKI-LAKI']);
        $preparedData['PEREMPUAN'] = array_values($preparedData['PEREMPUAN']);

        return $preparedData;
    }

    protected function getData(): array
    {
        if ($this->filter === 'Kelompok Umur') {

            $Umur = Penduduk::groupUJ()->get();
            $Kategori = ["0-4", "5-9", "10-14", "15-19", "20-24", "25-29", "30-34", "35-39", "40-44", "45-49", "50-54", "55-59", "60-64", "65-69", "70-74", "75+"];

            $totals = self::groupByGender($Umur);
            $totals = self::prepareData($totals, $Kategori);
        } else {

            $Umur = Penduduk::satuanUJ()->get();
            $Kategori = [
                "0", "1", "2", "3", "4", "5", "6", "7", "8", "9",
                "10", "11", "12", "13", "14", "15", "16", "17", "18", "19",
                "20", "21", "22", "23", "24", "25", "26", "27", "28", "29",
                "30", "31", "32", "33", "34", "35", "36", "37", "38", "39",
                "40", "41", "42", "43", "44", "45", "46", "47", "48", "49",
                "50", "51", "52", "53", "54", "55", "56", "57", "58", "59",
                "60", "61", "62", "63", "64", "65", "66", "67", "68", "69",
                "70", "71", "72", "73", "74", "75+"
            ];

            $totals = self::groupByGender($Umur);
            $totals = self::prepareData($totals, $Kategori);
        }

        $totals['PEREMPUAN'] = array_map(function ($item) {
            return -1 * $item;
        }, $totals['PEREMPUAN']);


        return [
            'datasets' => [
                [
                    'label' => 'Laki-laki',
                    'data' => $totals['LAKI-LAKI'],
                    'barWidth' => '20px', // default: '10px
                    'barPercentage' => 1,
                    'backgroundColor' => ' rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Perempuan',
                    'data' => $totals['PEREMPUAN'],
                    'barPercentage' => 1,
                    'barWidth' => '20px', // default: '10px
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $totals['UMUR'],
        ];
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<JS
        {
            aspectRatio:1,
            indexAxis: 'y',
            scales: {
            
                x: {
                    min: -150,
                    max: 150,
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
            'Kelompok Umur' => 'Kelompok Umur',
            'Satuan Umur' => 'Satuan Umur',
        ];
    }
}
