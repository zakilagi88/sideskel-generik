<?php

namespace App\Livewire\Widgets;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\SLS;
use App\Models\Wilayah;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Component;

class StatsOverview extends Component
{
    public function render()
    {
        $kk = KartuKeluarga::count();
        $pdd = Penduduk::count();
        $rt = Wilayah::count();
        return view('livewire.widgets.stats-overview', compact('kk', 'pdd', 'rt'));
    }


    public function getData()
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
