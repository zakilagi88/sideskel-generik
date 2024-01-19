<?php

namespace App\Livewire\Widgets\Chart;


use App\Livewire\Widgets\Table\RentangUmurTable;
use App\Livewire\Widgets\Table\SatuanUmurTable;
use App\Models\Penduduk;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class PendudukChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penduduk berdasarkan Jenis Kelamin dan Kelompok Umur';

    protected static string $color = 'success';

    protected static ?string $maxHeight = '800px';

    public ?string $filter = 'Rentang Umur';

    public $umurSeries = [];
    public $lkSeries = [];
    public $prSeries = [];
    public $totalSeries = [];


    protected function getData(): array
    {
        if ($this->filter === 'Rentang Umur') {

            $datatabel = app(RentangUmurTable::class)->data();
            $items = $datatabel['data'];
            $umurSeries = array_column($items, 'RENTANG_UMUR');
            $lkSeries = array_column($items, 'LAKI-LAKI');
            $prSeries = array_column($items, 'PEREMPUAN');
            $totalSeries = array_column($items, 'TOTAL');
        } else {
            $datatabel = app(SatuanUmurTable::class)->data();
            $items = $datatabel['data'];
            $umurSeries = array_column($items, 'SATUAN_UMUR');
            $lkSeries = array_column($items, 'LAKI-LAKI');
            $prSeries = array_column($items, 'PEREMPUAN');
            $totalSeries = array_column($items, 'TOTAL');
        }

        array_walk_recursive($prSeries, function (&$item) {
            $item = -1 * $item;
        });


        return [
            'datasets' => [
                [
                    'label' => 'Laki-laki',
                    'data' => $lkSeries,
                    'barWidth' => '20px', // default: '10px
                    'barPercentage' => 1,
                    'backgroundColor' => ' rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Perempuan',
                    'data' => $prSeries,
                    'barPercentage' => 1,
                    'barWidth' => '20px', // default: '10px
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $umurSeries,
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