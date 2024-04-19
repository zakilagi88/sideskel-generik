<?php

namespace App\Livewire\Widgets\Tables\Penduduk;

use App\Enums\Kependudukan\AgamaType;
use App\Models\{Penduduk\PendudukAgama};
use App\Models\Penduduk\PendudukView;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use PhpParser\Node\Stmt\Static_;

class AgamaTable extends Component implements HasTable, HasForms
{
    use InteractsWithPageFilters, InteractsWithTable, InteractsWithForms;

    public function render()
    {
        return view('livewire.statistik.tabel');
    }

    public function table(Table $table): Table
    {
        $activeFilters = $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;

        return $table
            ->query(
                PendudukView::getView(AgamaType::class, 'agama', $activeFilters)
                    ->when(
                        isset($this->filters['key']) && $this->filters['key'] !== [],
                        function (Builder $query) {
                            $query->whereIn('agama', $this->filters['key']);
                        }
                    )
            )
            ->queryStringIdentifier('agama')
            ->heading('Tabel Penduduk Berdasarkan Agama')
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter(),
                TextColumn::make('agama')
                    ->label('Agama')
                    ->alignJustify(),
                TextColumn::make('laki_laki')
                    ->label('Laki-laki')
                    ->alignCenter()
                    ->summarize(Sum::make()),
                TextColumn::make('perempuan')
                    ->label('Perempuan')
                    ->alignCenter()
                    ->summarize(Sum::make()),
                TextColumn::make('total')
                    ->label('Total')
                    ->alignCenter()
                    ->summarize(Sum::make()),
            ])
            ->deferLoading()
            ->deferFilters()
            ->paginated(false)
            ->persistFiltersInSession()
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->defaultSort('total', 'desc')
            ->striped();
    }

    // public function getTableEloquentQuery(null|array $filters): Builder
    // {
    //     return PendudukAgama::query()
    //         ->when(isset($filters['key']) && $filters['key'] !== [], function (Builder $query) use ($filters) {
    //             $query->whereIn('agama', $filters['key']);
    //         })
    //         ->when(isset($filters['parent_id']) && $filters['parent_id'] !== '' && $filters['parent_id'] !== null, function (Builder $query) use ($filters) {
    //             $query->where('parent_id', $filters['parent_id']);
    //         })
    //         ->when(isset($filters['children_id']) && $filters['children_id'] !== '' && $filters['children_id'] !== null, function (Builder $query) use ($filters) {
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
