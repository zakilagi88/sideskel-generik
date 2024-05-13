<?php

namespace App\Livewire\Widgets\Tables;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Penduduk\PendudukView;
use App\Models\StatSDM;
use App\Models\Tambahan;
use App\Models\Tambahanable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Livewire\Component;

class StatTambahanTable extends Component implements HasTable, HasForms
{
    use InteractsWithPageFilters, InteractsWithTable, InteractsWithForms;


    public Model | int | string | null $record;

    // public function mount($record)
    // {
    //     dd(Tambahanable::query()->with(['tambahan', 'tambahanable']));
    // }

    public function render()
    {
        return view('livewire.pages.stat.tabel');
    }

    // protected function getTableQuery($record, $filters): Builder|Relation|null
    // {
    //     $query = Tambahanable::query()->get();

    //     return $query;
    // }

    public function table(Table $table): Table
    {
        $activeFilters = $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;
        return $table
            ->query(
                Tambahanable::with(['tambahan'])
                    ->where('tambahan_id', fn ($query) => $query->select('id')->from('tambahans')->where('slug', $this->record->slug))
                    ->when(
                        $this->record->sasaran == 'Penduduk',
                        function ($query) {
                            $query->selectRaw('
                                tambahan_id,
                                tambahanable_ket, 
                                SUM(CASE WHEN p.jenis_kelamin = "Laki-laki" THEN 1 ELSE 0 END) as laki_laki,
                                SUM(CASE WHEN p.jenis_kelamin = "Perempuan" THEN 1 ELSE 0 END) as perempuan,
                                COUNT(*) as total
                            ');
                            $query->leftJoin('penduduk as p', 'tambahanable_id', '=', 'p.nik')
                                ->where('tambahanable_type', Penduduk::class);
                            $query->leftJoin('kartu_keluarga as kk', 'p.kk_id', '=', 'kk.kk_id');
                            $query->leftJoin('wilayah', 'kk.wilayah_id', '=', 'wilayah.wilayah_id');
                        }
                    )
                    ->when(
                        $this->record->sasaran == 'Keluarga',
                        function ($query) {
                            $query->selectRaw('
                                tambahan_id,
                                tambahanable_ket, 
                                SUM(CASE WHEN p.jenis_kelamin = "Laki-laki" THEN 1 ELSE 0 END) as laki_laki,
                                SUM(CASE WHEN p.jenis_kelamin = "Perempuan" THEN 1 ELSE 0 END) as perempuan,
                                COUNT(*) as total
                            ');
                            $query->leftJoin('kartu_keluarga as kk', 'tambahanable_id', '=', 'kk.kk_id')
                                ->where('tambahanable_type', KartuKeluarga::class);
                            $query->leftJoin('penduduk as p', 'kk.kk_id', '=', 'p.kk_id')
                                ->where('p.status_hubungan', 'KEPALA KELUARGA');
                            $query->leftJoin('wilayah', 'kk.wilayah_id', '=', 'wilayah.wilayah_id');
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
                    ->groupBy('tambahanable_ket')
            )
            ->queryStringIdentifier(identifier: 'id')
            ->heading(
                fn () => 'Tabel Penduduk Berdasarkan ' . ucfirst($this->record->nama)
            )
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex()
                    ->alignCenter(),
                TextColumn::make('tambahanable_ket')
                    ->label('Keterangan')
                    ->formatStateUsing(
                        function ($state) {
                            return ucwords(str_replace('-', ' ', $state));
                        }
                    )
                    ->alignJustify(),
                // TextColumn::make(name: $this->record->key)
                //     ->label(
                //         fn () => ucfirst($this->record->nama)
                //     )P
                //     ->alignJustify(),
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
            ->defaultSort('tambahan_id', 'asc');
    }
}