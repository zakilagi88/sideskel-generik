<?php

namespace App\Livewire\Widgets\Tables;

use App\Livewire\Widgets\Charts\StatSDMBarChart;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Penduduk\PendudukView;
use App\Models\StatSDM;
use App\Models\Tambahan;
use App\Models\Tambahanable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class StatSDMTable extends Component implements HasTable, HasForms
{
    use InteractsWithPageFilters, InteractsWithTable, InteractsWithForms;

    public Model | int | string | null $record;

    public function render()
    {
        return view('livewire.pages.stat.tabel');
    }

    public function table(Table $table): Table
    {

        return $table
            ->query(
                $this->getEloquentQuery()
            )
            ->queryStringIdentifier(identifier: $this->getRecordKey())
            ->heading(
                fn () => 'Tabel Penduduk Berdasarkan ' . ucfirst($this->record->nama)
            )
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter(),
                TextColumn::make($this->getRecordKey())

                    ->label(
                        'Keterangan ' . ($this->record instanceof StatSDM ? ucwords($this->getRecordKey()) : ucwords($this->record->nama))
                    )
                    ->formatStateUsing(
                        function ($state) {
                            return $this->record instanceof StatSDM ? $state : ucwords(str_replace('-', ' ', $state));
                        }
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
            ->persistColumnSearchesInSession()
            ->persistSearchInSession()
            ->persistSortInSession()
            ->defaultSort($this->getTableSort(), 'asc');
    }

    protected function getFilterWilayah()
    {
        return $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;
    }

    protected function getTableSort()
    {
        if ($this->record instanceof StatSDM) {
            return 'id';
        } else {
            return 'tambahan_id';
        }
    }

    protected function getRecordKey()
    {
        if ($this->record instanceof StatSDM) {
            return $this->record->key;
        } else {
            return 'tambahanable_ket';
        }
    }

    protected function getEloquentQuery(): Builder
    {
        $activeFilters = $this->getFilterWilayah();
        $query = null;

        if ($this->record instanceof Tambahan) {
            $query = $this->getTambahanQuery($activeFilters);
        } else {
            $query = $this->getPendudukViewQuery($activeFilters);
        }

        return $query;
    }

    protected function getTambahanQuery($activeFilters): Builder
    {

        return
            Tambahanable::with(['tambahan'])
            ->where('tambahan_id', fn ($query) => $query->select('id')->from('tambahans')->where('slug', $this->record->slug))
            ->selectRaw('
                                tambahan_id,
                                tambahanable_ket, 
                                SUM(CASE WHEN p.jenis_kelamin = "Laki-laki" THEN 1 ELSE 0 END) as laki_laki,
                                SUM(CASE WHEN p.jenis_kelamin = "Perempuan" THEN 1 ELSE 0 END) as perempuan,
                                COUNT(*) as total
                            ')
            ->when(
                $this->record->sasaran == 'Penduduk',
                function ($query) {
                    $query->leftJoin('penduduk as p', 'tambahanable_id', '=', 'p.nik')
                        ->where('tambahanable_type', Penduduk::class);
                    $query->leftJoin('kartu_keluarga as kk', 'p.kk_id', '=', 'kk.kk_id');
                    $query->leftJoin('wilayah as w', 'kk.wilayah_id', '=', 'w.wilayah_id');
                }
            )
            ->when(
                $this->record->sasaran == 'Keluarga',
                function ($query) {
                    $query->leftJoin('kartu_keluarga as kk', 'tambahanable_id', '=', 'kk.kk_id')
                        ->where('tambahanable_type', KartuKeluarga::class);
                    $query->leftJoin('penduduk as p', 'kk.kk_id', '=', 'p.kk_id')
                        ->where('p.status_hubungan', 'KEPALA KELUARGA');
                    $query->leftJoin('wilayah as w', 'kk.wilayah_id', '=', 'w.wilayah_id');
                }
            )
            ->when(
                !empty($this->filters['key']),
                function (Builder $query) {
                    $query->whereIn('tambahanable_ket', $this->filters['key']);
                }
            )
            ->when(
                empty($this->filters['tampilkan_null']) || !($this->filters['tampilkan_null']),
                function (Builder $query) {
                    $query->having('total', '>', 0);
                }
            )
            ->when(
                !empty($activeFilters),
                function ($query) use ($activeFilters) {
                    $query->where(function ($subquery) use ($activeFilters) {
                        $subquery->where('wilayah.wilayah_id', $activeFilters)
                            ->orWhere('wilayah.parent_id', $activeFilters);
                    });
                }
            )
            ->groupBy('tambahanable_ket');
    }

    protected function getPendudukViewQuery($activeFilters): Builder
    {
        $query =
            PendudukView::getView(key: $this->record->key, wilayahId: $activeFilters)
            ->when(
                !empty($this->filters['key']),
                function (Builder $query) {
                    $query->whereIn($this->record->key, $this->filters['key']);
                }
            )->when(
                empty($this->filters['tampilkan_null']) || !($this->filters['tampilkan_null']),
                function (Builder $query) {
                    $query->where('total', '!=', 0);
                }
            )
            ->when(
                $this->record->key === 'rentang_umur',
                function (Builder $query) {
                    $query->orderByRaw("CAST(SUBSTRING_INDEX(rentang_umur, '-', 1) AS UNSIGNED)");
                }
            )->when(
                $this->record->key === 'umur',
                function (Builder $query) {
                    $query->orderByRaw("CAST(umur AS UNSIGNED)");
                }
            );
        return $query;
    }
}
