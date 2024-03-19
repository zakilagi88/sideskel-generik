<?php

namespace App\Livewire\Widgets\Tables\Dinamika;

use App\Models\{Penduduk\PendudukAgama, RekapitulasiBulanan};
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;

class DinamikaTable extends TableWidget
{
    use InteractsWithPageFilters;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $bulan = date('m');
        $tahun = date('Y');
        $wilayah = null;
        return $table
            ->query(RekapitulasiBulanan::getRekapitulasi($bulan, $tahun, $wilayah))
            ->queryStringIdentifier('dinamika')
            ->columns([
                TextColumn::make('id')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('Perincian')
                    ->label('Perincian')
                    ->alignLeft()
                    ->sortable(),
                TextColumn::make('Laki_Laki')
                    ->label('Laki-laki')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('Perempuan')
                    ->label('Perempuan')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('Total')
                    ->label('Total')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->deferLoading()
            ->deferFilters()
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->striped();
    }

    // public function getTableEloquentQuery(array $filters): Builder
    // {
    //     return PendudukAgama::query()
    //         ->when($filters['agama'] !== [], function (Builder $query) use ($filters) {
    //             $query->whereIn('agama', $filters['agama']);
    //         })
    //         ->when($filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
    //             $query->where('parent_id', $filters['parent_id']);
    //         })
    //         ->when($filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
    //             $query->where('wilayah_id', $filters['children_id']);
    //         })
    //         ->select(
    //             'id',
    //             'agama',
    //             'parent_id',
    //             'wilayah_id',
    //             DB::raw('SUM(laki_laki) AS laki_laki'),
    //             DB::raw('SUM(perempuan) AS perempuan'),
    //             DB::raw('SUM(total) AS total')
    //         )
    //         ->orderBy('total', 'desc')
    //         ->groupBy('agama');
    // }
}
