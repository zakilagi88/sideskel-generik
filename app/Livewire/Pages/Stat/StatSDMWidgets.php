<?php

namespace App\Livewire\Pages\Stat;

use Livewire\Component;
use App\Livewire\Widgets\Charts\Stat\SDMBarChart;
use App\Livewire\Widgets\Charts\Stat\SDMPieChart;
use App\Livewire\Widgets\Tables\StatSDMTable;
use App\Models\{Penduduk, Penduduk\PendudukView, Tambahan, Tambahanable, Wilayah};
use App\Services\GenerateEnumUnionQuery;
use Filament\Forms\Components\{Group, Livewire, Section, Select, Tabs, Toggle};
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\{Form, Get, Set};
use Filament\Support\Enums\IconPosition;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;

class StatSDMWidgets extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $filters = [];

    public Model | int | string | null $record;

    public function render()
    {
        return view('livewire.stat.filter-form');
    }

    public function mount(int | string $record): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('filters')
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Filter')
                            ->collapsible()
                            ->columnSpanFull()
                            ->columns(3)
                            ->schema([
                                Select::make(name: 'key')
                                    ->hiddenLabel()
                                    ->placeholder(
                                        fn () => 'Pilih ' . $this->record->nama
                                    )
                                    ->multiple()
                                    ->live(onBlur: true)
                                    ->searchable()
                                    ->options(
                                        function () {
                                            if ($this->record instanceof Tambahan) {
                                                return $this->getTambahanOptions();
                                            } else {
                                                return GenerateEnumUnionQuery::getEnumClassByKeyName($this->record->key);
                                            }
                                        }
                                    ),
                                Select::make('parent_id')
                                    ->hiddenLabel()
                                    ->placeholder('Pilih wilayah')
                                    ->searchable()
                                    ->live()
                                    ->afterStateUpdated(
                                        function (Set $set) {
                                            $set('children_id', null);
                                        }
                                    )
                                    ->options(
                                        function () {
                                            return Wilayah::tree()->get()->where('depth', 0)->pluck('wilayah_nama', 'wilayah_id');
                                        }
                                    ),
                                Select::make('children_id')
                                    ->hidden(
                                        function (Get $get) {
                                            if (is_null($get('parent_id'))) {
                                                return true;
                                            }
                                            return false;
                                        }
                                    )
                                    ->hiddenLabel()
                                    ->placeholder('Pilih wilayah')
                                    ->searchable()
                                    ->reactive()
                                    ->options(
                                        function (Get $get) {
                                            return wilayah::where('parent_id', $get('parent_id'))->pluck('wilayah_nama', 'wilayah_id');
                                        }
                                    ),
                                Toggle::make('tampilkan_null')
                                    ->label('Tampilkan Nilai Kosong')
                                    ->onIcon('fas-check')
                                    ->offIcon('fas-times')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->inline()
                                    ->live()
                                    ->default(false),
                            ]),
                        Tabs::make('Tabs')
                            ->columnSpanFull()
                            ->tabs([
                                Tabs\Tab::make('Tabel')
                                    ->icon('fas-table-columns')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        Livewire::make(StatSDMTable::class, ['filters' => $this->filters, 'record' => $this->record])
                                            ->key('table1')
                                            ->hiddenLabel(),

                                    ]),
                                Tabs\Tab::make('Grafik')
                                    ->icon('fas-chart-pie')
                                    ->iconPosition(IconPosition::After)
                                    ->schema([
                                        Tabs::make('Tabs')
                                            ->contained(false)
                                            ->tabs([
                                                Tabs\Tab::make('Bar')
                                                    ->schema([
                                                        Livewire::make(SDMBarChart::class, ['filters' => $this->filters, 'record' => $this->record, 'chartData' => $this->chartData])
                                                            ->key('bar')
                                                            ->hiddenLabel()
                                                    ]),
                                                Tabs\Tab::make('Pie ')
                                                    ->schema([
                                                        Livewire::make(SDMPieChart::class, ['filters' => $this->filters, 'record' => $this->record, 'chartData' => $this->chartData])
                                                            ->key('pie')
                                                            ->hiddenLabel(),
                                                    ]),
                                            ]),
                                    ])
                            ]),
                    ])
            ]);
    }

    protected function getTambahanOptions()
    {
        $options = Tambahan::aktif()
            ->where('slug', $this->record->slug)
            ->pluck('kategori')
            ->flatMap(function ($items) {
                return collect($items)->flatMap(function ($item) {
                    $cleanedItem = strtolower(str_replace(' ', '-', $item));
                    return [$cleanedItem => $item];
                });
            })
            ->toArray();

        return $options;
    }

    protected function getFilterWilayah()
    {
        return $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;
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
                        SUM(CASE WHEN penduduk.jenis_kelamin = "Laki-laki" THEN 1 ELSE 0 END) as laki_laki,
                        SUM(CASE WHEN penduduk.jenis_kelamin = "Perempuan" THEN 1 ELSE 0 END) as perempuan,
                        COUNT(*) as total
                    ')
            ->leftJoin('penduduk', function ($join) {
                $join->on('tambahanable_id', '=', 'penduduk.nik')
                    ->where('tambahanable_type', Penduduk::class);
            })
            ->leftJoin('kartu_keluarga', 'penduduk.kk_id', '=', 'kartu_keluarga.kk_id')
            ->leftJoin('wilayah', 'kartu_keluarga.wilayah_id', '=', 'wilayah.wilayah_id')
            ->when(
                !empty($this->filters['key']),
                function (Builder $query) {
                    $query->whereIn('tambahanable_ket', $this->filters['key']);
                }
            )
            ->when(
                empty($this->filters['tampilkan_null']) || !($this->filters['tampilkan_null']),
                function (Builder $query) {
                    $query->having('total', '!=', 0);
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
        $query = PendudukView::getView(key: $this->record->key, wilayahId: $activeFilters)
            ->when(
                !empty($this->filters['key']),
                function (Builder $query) {
                    $query->whereIn($this->record->key, $this->filters['key']);
                }
            )
            ->when(
                empty($this->filters['tampilkan_null']) || !($this->filters['tampilkan_null']),
                function (Builder $query) {
                    $query->where('total', '!=', 0);
                }
            );

        if ($this->record->key === 'rentang_umur') {
            $query->orderByRaw("CAST(SUBSTRING_INDEX(rentang_umur, '-', 1) AS UNSIGNED)");
        }

        return $query;
    }

    #[Computed()]
    public function chartData(): array
    {
        return $this->getEloquentQuery()->get()->toArray();
    }
}
