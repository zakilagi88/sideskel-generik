<?php

namespace App\Livewire\Widgets;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Models\{Dinamika, KartuKeluarga, Kelahiran, Kematian, Kepindahan, Lembaga, Pendatang, Penduduk, Wilayah};
use App\Models\Desa\Aparatur;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('Aparatur Desa', Aparatur::count())
                ->icon('fas-user-tie')
                ->description('Jumlah Aparatur Desa')
                ->chartColor('primary')
                ->color('primary')
                ->url(AparaturResource::getUrl('index'))
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Lembaga Desa', Lembaga::count())
                ->icon('fas-users')
                ->description('Jumlah Lembaga Desa')
                ->chartColor('primary')
                ->color('primary')
                ->url(LembagaResource::getUrl('index'))
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
                ->url(WilayahResource::getUrl('index'))
                ->color('warning')
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Kartu Keluarga', KartuKeluarga::query()->whereHas('kepalaKeluarga', fn ($query) => $query->where('status_dasar', 'HIDUP'))->count())
                ->icon('fas-people-roof')
                ->description('Kepala Keluarga')
                ->chartColor('success')
                ->color('success')
                ->url(KartuKeluargaResource::getUrl('index'))
                ->chart([
                    20, 10, 3, 12, 1, 14, 10, 1, 4, 20
                ])
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk', Penduduk::where('status_dasar', 'HIDUP')->count())
                ->icon('fas-person')
                ->description('Jumlah Penduduk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->descriptionColor('info')
                ->chartColor('info')
                ->url(PendudukResource::getUrl('index'))
                ->color('info')
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
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Pendatang']))
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
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Pindah']))

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
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Meninggal']))
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
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Pendatang']))
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
