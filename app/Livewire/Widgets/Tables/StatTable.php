<?php

namespace App\Livewire\Widgets\Tables;

use App\Enums\Kependudukan\AgamaType;
use App\Models\Penduduk\PendudukView;
use App\Models\Stat;
use App\Services\GenerateEnumUnionQuery;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Component;

class StatTable extends Component implements HasTable, HasForms
{
    use InteractsWithPageFilters, InteractsWithTable, InteractsWithForms;

    public Stat $stat;

    public function render()
    {
        return view('livewire.stat.tabel');
    }

    public function table(Table $table): Table
    {
        $activeFilters = $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;
        return $table
            ->query(
                PendudukView::getView(key: $this->stat->key, wilayahId: $activeFilters)
                    ->when(
                        isset($this->filters['key']) && $this->filters['key'] !== [],
                        function (Builder $query) {
                            $query->whereIn($this->stat->key, $this->filters['key']);
                        }
                    )->when(
                        $this->filters['tampilkan_null'] === false,
                        function (Builder $query) {
                            $query->where('total', '!=', 0);
                        }
                    )
            )
            ->queryStringIdentifier(identifier: $this->stat->key)
            ->heading(
                fn () => 'Tabel Penduduk Berdasarkan ' . ucfirst($this->stat->nama)
            )
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter(),
                TextColumn::make(name: $this->stat->key)
                    ->label(
                        fn () => ucfirst($this->stat->nama)
                    )
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
}