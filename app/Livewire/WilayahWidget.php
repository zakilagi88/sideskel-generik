<?php

namespace App\Livewire;

use App\Models\Wilayah;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class WilayahWidget extends BaseWidget
{

    protected function getColumns(): int
    {
        return 2;
    }
    protected function getStats(): array
    {
        $cek = Wilayah::count();
        if ($cek == 0) {
            return [];
        } else {
            return [
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
}
