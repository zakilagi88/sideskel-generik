<?php

namespace App\Livewire\Widgets;

use App\Models\{Dinamika, KartuKeluarga, Kelahiran, Kematian, Kepindahan, Pendatang, Penduduk, Wilayah};
use App\Models\Desa\Aparatur;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 8;

    protected function getStats(): array
    {
        return [
            Stat::make('Aparatur Desa', Aparatur::count())
                ->icon('heroicon-o-user-group')
                ->description('Jumlah Aparatur Desa')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chartColor('primary')

                ->color('primary')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Kartu Keluarga', KartuKeluarga::count())
                ->icon('heroicon-o-user-group')
                ->description('Kepala Keluarga')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chartColor('success')

                ->color('success')
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
                ->descriptionColor('info')
                ->chartColor('info')

                ->color('info')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Wilayah', Wilayah::count())
                ->icon('fas-map')
                ->description('Jumlah Wilayah')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('warning')
                ->chartColor('warning')

                ->color('warning')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk Masuk', Dinamika::where('dinamika_type', Pendatang::class)->count())
                ->icon('fas-person-circle-plus')
                ->description('Penduduk Masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('info')
                ->chartColor('info')

                ->color('info')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk Keluar', Dinamika::where('dinamika_type', Kepindahan::class)->count())
                ->icon('fas-person-circle-minus')
                ->description('Penduduk Keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('warning')
                ->chartColor('warning')

                ->color('danger')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk Meninggal', Dinamika::where('dinamika_type', Kematian::class)->count())
                ->icon('fas-person-circle-xmark')
                ->description('Penduduk Meninggal')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('danger')
                ->chartColor('danger')

                ->color('danger')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk Lahir', Dinamika::where('dinamika_type', Kelahiran::class)->count())
                ->icon('fas-person-breastfeeding')
                ->description('Penduduk Lahir')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('info')
                ->chartColor('info')

                ->color('info')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}
