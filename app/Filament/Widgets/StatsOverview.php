<?php

namespace App\Filament\Widgets;

use App\Models\{KartuKeluarga, Penduduk, Wilayah};
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Kartu Keluarga', KartuKeluarga::count())
                ->icon('heroicon-o-user-group')
                ->description('Kepala Keluarga')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chartColor('primary')

                ->color('primary')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk', Penduduk::count())
                ->icon('heroicon-o-user-group')
                ->description('Jumlah Penduduk')
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
            Stat::make('RT', Wilayah::count())
                ->icon('heroicon-o-user-group')
                ->description('Jumlah RT')
                ->descriptionIcon('heroicon-o-user-group')
                ->descriptionColor('danger')
                ->chartColor('danger')

                ->color('danger')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ]),
        ];
    }
}
