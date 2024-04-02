<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Widgets;

use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages\ListWilayahs;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class WilayahOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected int | string | array $columnSpan = 'full';

    protected function getTablePage(): string
    {
        return ListWilayahs::class;
    }

    protected function getStats(): array
    {
        // $pddData = Trend::model(Wilayah::class)
        //     ->between(
        //         start: now()->subMonth(),
        //         end: now(),
        //     )
        //     ->perDay()
        //     ->count();
        return [
            Stat::make('Total Wilayah', $this->getPageTableQuery()->count())
                ->chart(
                    [
                        20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                    ]
                )->color('primary')->icon('fas-people-group'),
            Stat::make('RW', Wilayah::tree()->get()->where('depth', 0)->count())
                ->icon('heroicon-o-user-group')
                ->description('Jumlah RW')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chartColor('primary')
                ->color('primary')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('RT', Wilayah::tree()->get()->where('depth', 1)->count())
                ->icon('heroicon-o-user-group')
                ->description('Jumlah RT')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('success')
                ->chartColor('success')
                ->color('success')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

        ];
    }
}
