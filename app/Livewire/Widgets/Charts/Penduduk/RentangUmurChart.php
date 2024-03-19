<?php

namespace App\Livewire\Widgets\Charts\Penduduk;


use App\Models\Penduduk\PendudukRentangUmur;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RentangUmurChart extends ApexChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $loadingIndicator = 'Loading...';
    protected static ?string $chartId = 'rentang-umur-chart';
    protected static bool $deferLoading = true;
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Grafik Penduduk Berdasarkan Rentang Umur';

    protected function getData(): array
    {
        $items = static::getTableEloquentQuery($this->filters)->get()->toArray();

        return [
            'rentang_umur' => array_column($items, 'rentang_umur'),
            'laki_laki' => array_column($items, 'laki_laki'),
            'perempuan' => array_column($items, 'perempuan'),
            'total' => array_column($items, 'total'),
        ];
    }

    public function getTableEloquentQuery(array $filters): Builder
    {
        return PendudukRentangUmur::query()
            ->when($filters['rentang_umur'] !== [], function (Builder $query) use ($filters) {
                $query->whereIn('rentang_umur', $filters['rentang_umur']);
            })
            ->when($filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->when($filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('wilayah_id', $filters['children_id']);
            })
            ->select(
                'id',
                'rentang_umur',
                'parent_id',
                'wilayah_id',
                DB::raw('SUM(laki_laki) AS laki_laki'),
                DB::raw('SUM(perempuan) AS perempuan'),
                DB::raw('SUM(total) AS total')
            )
            ->orderBy('total', 'desc')
            ->groupBy('rentang_umur');
    }


    protected function getOptions(): array
    {

        if (!$this->readyToLoad) {
            return [];
        }

        sleep(1);

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
                'categories' => $data['rentang_umur'],
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
