<?php

namespace App\Livewire\Widgets\Tables\Penduduk;

use App\Enums\Kependudukan\PendidikanType;
use App\Models\{Penduduk\PendudukPendidikan, RT, RW};
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PendidikanTable extends TableWidget
{

    use InteractsWithPageFilters;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                static::getTableEloquentQuery($this->filters)
            )
            ->queryStringIdentifier('pendidikan')
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('pendidikan')
                    ->label('Pendidikan')
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
            ->striped();
    }

    public function getTableEloquentQuery(array $filters): Builder
    {
        return PendudukPendidikan::query()
            ->when($filters['pendidikan'] !== [], function (Builder $query) use ($filters) {
                $query->whereIn('pendidikan', $filters['pendidikan']);
            })
            ->when($filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->when($filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('wilayah_id', $filters['children_id']);
            })
            ->select(
                'id',
                'pendidikan',
                'parent_id',
                'wilayah_id',
                DB::raw('SUM(laki_laki) AS laki_laki'),
                DB::raw('SUM(perempuan) AS perempuan'),
                DB::raw('SUM(total) AS total')
            )
            ->orderBy('total', 'desc')
            ->groupBy('pendidikan');
    }
}
