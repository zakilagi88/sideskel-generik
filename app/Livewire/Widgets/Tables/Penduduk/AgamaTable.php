<?php

namespace App\Livewire\Widgets\Tables\Penduduk;

use App\Models\{Penduduk\PendudukAgama};
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AgamaTable extends TableWidget
{
    use InteractsWithPageFilters;

    public function table(Table $table): Table
    {

        return $table
            ->query(
                static::getTableEloquentQuery($this->filters)
            )
            ->queryStringIdentifier('agama')
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('agama')
                    ->label('Agama')
                    ->alignJustify()
                    ->sortable(),
                TextColumn::make('laki_laki')
                    ->label('Laki-laki')
                    ->alignCenter()
                    ->summarize(Sum::make())
                    ->sortable(),
                TextColumn::make('perempuan')
                    ->label('Perempuan')
                    ->alignCenter()
                    ->summarize(Sum::make())
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->alignCenter()
                    ->summarize(Sum::make())
                    ->sortable(),
            ])
            ->deferLoading()
            ->deferFilters()
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->defaultSort('total', 'desc')
            ->striped();
    }

    public function getTableEloquentQuery(array $filters): Builder
    {
        return PendudukAgama::query()
            ->when($filters['agama'] !== [], function (Builder $query) use ($filters) {
                $query->whereIn('agama', $filters['agama']);
            })
            ->when($filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->when($filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('wilayah_id', $filters['children_id']);
            })
            ->select(
                'id',
                'agama',
                'parent_id',
                'wilayah_id',
                DB::raw('SUM(laki_laki) AS laki_laki'),
                DB::raw('SUM(perempuan) AS perempuan'),
                DB::raw('SUM(total) AS total')
            )
            ->orderBy('total', 'desc')
            ->groupBy('agama');
    }
}
