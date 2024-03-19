<?php

namespace App\Livewire\Widgets\Tables\Penduduk;

use App\Enums\Kependudukan\PerkawinanType;
use App\Models\{Penduduk\PendudukPerkawinan, RT, RW};
use Filament\Forms\Components\Group;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PerkawinanTable extends TableWidget
{
    use InteractsWithPageFilters;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                static::getTableEloquentQuery($this->filters)
            )
            ->queryStringIdentifier('status_perkawinan')
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('status_perkawinan')
                    ->label('Perkawinan')
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
        return PendudukPerkawinan::query()
            ->when($filters['status_perkawinan'] !== [], function (Builder $query) use ($filters) {
                $query->whereIn('status_perkawinan', $filters['status_perkawinan']);
            })
            ->when($filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('parent_id', $filters['parent_id']);
            })
            ->when($filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
                $query->where('wilayah_id', $filters['children_id']);
            })
            ->select(
                'id',
                'status_perkawinan',
                'parent_id',
                'wilayah_id',
                DB::raw('SUM(laki_laki) AS laki_laki'),
                DB::raw('SUM(perempuan) AS perempuan'),
                DB::raw('SUM(total) AS total')
            )
            ->orderBy('total', 'desc')
            ->groupBy('status_perkawinan');
    }
}
