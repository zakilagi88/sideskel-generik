<?php

namespace App\Livewire\Widgets;

use App\Filament\Clusters\HalamanArsip;
use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\KartuKeluargaResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource;
use App\Models\Deskel\{Aparatur, Dokumen, Lembaga};
use App\Models\{Dinamika, KartuKeluarga, Kelahiran, Kematian, Kepindahan, Pendatang, Penduduk, Wilayah};
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class StatsOverview extends BaseWidget
{

    public $dinamika;
    protected int | string | array $columnSpan = 2;
    protected static ?string $pollingInterval = '5s';

    public function mount()
    {
        $this->dinamika = Dinamika::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereIn('dinamika_type', [Kematian::class, Pendatang::class, Kepindahan::class])
            ->selectRaw('
                CASE 
                    WHEN dinamika_type = ? THEN "kematian_count" 
                    WHEN dinamika_type = ? THEN "pendatang_count" 
                    WHEN dinamika_type = ? THEN "pindah_count" 
                END as type, 
                COUNT(*) as count', [
                Kematian::class,
                Pendatang::class,
                Kepindahan::class
            ])
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type')
            ->toArray();
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Aparatur', Aparatur::count())
                ->icon('fas-user-tie')
                ->description('Jumlah Aparatur')
                ->color('primary')
                ->url(AparaturResource::getUrl('index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Lembaga', Lembaga::count())
                ->icon('fas-users')
                ->description('Jumlah Lembaga')
                ->color('primary')
                ->url(LembagaResource::getUrl('index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Dokumen', Dokumen::count())
                ->icon('fas-file-alt')
                ->description('Jumlah Dokumen')
                ->color('primary')
                ->url(HalamanArsip::getUrl())
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Wilayah', Wilayah::count())
                ->icon('fas-map')
                ->description('Jumlah Wilayah')
                ->url(WilayahResource::getUrl('index'))
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Keluarga', KartuKeluarga::count())
                ->icon('fas-people-roof')
                ->description('Jumlah Kepala Keluarga')
                ->color('success')
                ->url(KartuKeluargaResource::getUrl('index'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk', Penduduk::where('status_dasar', 'HIDUP')->count())
                ->icon('fas-person')
                ->description('Jumlah Penduduk')
                ->chartColor('success')
                ->url(PendudukResource::getUrl('index'))
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),

            Stat::make('Penduduk Masuk', $this->dinamika['pendatang_count'] ?? 0)
                ->icon('fas-person-circle-plus')
                ->description('Jumlah Penduduk Masuk')
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Pendatang']))
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk Keluar', $this->dinamika['pindah_count'] ?? 0)
                ->icon('fas-person-circle-minus')
                ->description('Jumlah Penduduk Keluar')
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Pindah']))
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
            Stat::make('Penduduk Meninggal', $this->dinamika['kematian_count'] ?? 0)
                ->icon('fas-person-circle-xmark')
                ->description('Jumlah Penduduk Meninggal')
                ->url(PendudukResource::getUrl('index', ['activeTab' => 'Meninggal']))
                ->color('info')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                ]),
        ];
    }
}
