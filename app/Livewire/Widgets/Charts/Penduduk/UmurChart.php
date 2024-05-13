<?php

namespace App\Livewire\Widgets\Charts\Penduduk;

use App\Livewire\Widgets\Tables\Penduduk\UmurTable;

use App\Models\Penduduk\PendudukUmur;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UmurChart extends ApexChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'umur-chart';
    protected static bool $deferLoading = true;
    public $pageQuery, $pageTable;
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Penduduk Berdasarkan Umur';

    protected function getData(): array
    {

        $items = static::getTableEloquentQuery($this->filters)->get()->toArray();

        return [
            'umur' => array_column($items, 'umur'),
            'laki_laki' => array_column($items, 'laki_laki'),
            'perempuan' => array_column($items, 'perempuan'),
            'total' => array_column($items, 'total'),
        ];
    }

    public function getTableEloquentQuery(array $filters): Builder
    {
        return PendudukUmur::query()
            ->when($filters['umur'] !== [], function (Builder $query) use ($filters) {
                $query->whereIn('umur', $filters['umur']);
            })
            ->when($filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->when($filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('wilayah_id', $filters['children_id']);
            })
            ->select(
                'id',
                'umur',
                'parent_id',
                'wilayah_id',
                DB::raw('SUM(laki_laki) AS laki_laki'),
                DB::raw('SUM(perempuan) AS perempuan'),
                DB::raw('SUM(total) AS total')
            )
            ->orderBy('umur', 'desc')
            ->groupBy('umur');
    }


    protected function getOptions(): array
    {

        if (!$this->readyToLoad) {
            return [];
        }

        sleep(5);

        $data = $this->getData();

        array_walk_recursive($data['perempuan'], function (&$item) {
            $item = -1 * $item;
        });

        return [
            'chart' => [
                'type' => 'bar',
                'height' => 1024,
                'stacked' => true,
            ],
            'series' => [
                [
                    'name' => 'Laki-laki',
                    'data' => $data['laki_laki'],
                ],
                [
                    'name' => 'Perempuan',
                    'data' => $data['perempuan'],
                ]
            ],
            'colors' => ['#6366f1', '#ec4899'],
            'plotOptions' => [
                'bar' => [
                    'borderRadius' => 2,
                    'barHeight' => '100%',
                    'horizontal' => true,
                ],
            ],
            'dataLabels' => [
                'enabled' => true,
            ],
            'grid' => [
                'xaxis' => [
                    'lines' => [
                        'show' => false,
                    ],
                ],
            ],
            'stroke' => [
                'width' => 1,
                'colors' => ['#fff'],
            ],
            'yaxis' => [
                'min' => -100,
                'max' => 100,
                'title' => [
                    'text' => 'Kelompok Umur',
                ],
            ],
            'xaxis' => [
                'categories' => $data['umur'],
            ]
        ];
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
                            return value
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
                        return Math.abs(val)
                    },
                    style: {
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
                            return Math.abs(Math.round(val)) + " Tahun"
                        }
                    },
                }
                
            }
        JS
        );
    }
}