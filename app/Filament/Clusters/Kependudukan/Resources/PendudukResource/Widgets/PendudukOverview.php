<?php

namespace App\Filament\Clusters\Kependudukan\Resources\PendudukResource\Widgets;

use App\Filament\Clusters\Kependudukan\Resources\PendudukResource\Pages\ListPenduduks;
use App\Models\Penduduk;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Arr;

class PendudukOverview extends BaseWidget
{
    use InteractsWithPageTable;
    protected int | string | array $columnSpan = 'full';
    // protected int | string | array $rowSpan = '1';


    // protected static string $view = 'filament.resources.penduduk-resource.widgets.penduduk-overview';

    protected function getTablePage(): string
    {
        return ListPenduduks::class;
    }

    protected function getStats(): array
    {
        $pddData = Trend::model(Penduduk::class)
            ->between(
                start: now()->subMonth(),
                end: now(),
            )
            ->perDay()
            ->count();
        return [
            Stat::make('Total Penduduk', $this->getPageTableQuery()->count())
                ->chart(
                    $pddData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                )->color('primary')->icon('fas-people-group'),
            Stat::make('Total Laki-laki', $this->getPageTableQuery()->where('jenis_kelamin', 'LAKI-LAKI')->count())
                ->chart(
                    $pddData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                )->color('success')->icon('fas-person'),
            Stat::make('Total Perempuan', $this->getPageTableQuery()->where('jenis_kelamin', 'PEREMPUAN')->count())
                ->chart(
                    $pddData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                )->color('danger')->icon('fas-person-dress'),

        ];
    }
}
