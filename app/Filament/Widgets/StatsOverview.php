<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 3;

    protected function getStats(): array
    {
        return [
            Stat::make('Kartu Keluarga', \App\Models\KartuKeluarga::count())
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
            Stat::make('Penduduk', \App\Models\Penduduk::count())
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
            Stat::make('RT', \App\Models\SLS::count())
                ->icon('heroicon-o-user-group')
                ->description('Jumlah RT')
                ->descriptionIcon('heroicon-o-user-group')
                ->descriptionColor('danger')
                ->chartColor('danger')

                ->color('danger')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ]),
            // Stat::make('RW', \App\Models\RW::count())
            //     ->description('Jumlah RW')
            //     ->descriptionIcon('heroicon-o-user-group')
            //     ->color('primary')
            //     ->chart([
            //         'labels' => \App\Models\RW::pluck('nama'),
            //         'values' => \App\Models\RW::pluck('anggotaKeluarga')->map->count(),
            //     ]),
            // Stat::make('SLS', \App\Models\SLS::count())
            //     ->description('Jumlah SLS')
            //     ->descriptionIcon('heroicon-o-user-group')
            //     ->color('primary')
            //     ->chart(
            //         [
            //             'labels' => \App\Models\SLS::pluck('nama'),
            //             'values' => \App\Models\SLS::pluck('anggotaKeluarga')->map->count(),
            //         ]
            //     ),
        ];
    }
}
