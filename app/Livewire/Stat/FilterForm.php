<?php

namespace App\Livewire\Stat;

use Livewire\Component;
use App\Enums\Kependudukan\AgamaType;
use App\Enums\Kependudukan\PekerjaanType;
use App\Enums\Kependudukan\PendidikanType;
use App\Models\Penduduk\PendudukView;
use App\Models\Stat;
use App\Models\StatKategori;
use App\Models\Wilayah;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Enums\Alignment;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Reactive;

class FilterForm extends Component implements HasForms
{

    use InteractsWithForms;

    public ?array $filters = [];

    #[Reactive]
    public $activeTab;

    public Stat $stat;

    public function render()
    {
        return view('livewire.stat.filter-form');
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('filters')
            ->schema([
                Grid::make(
                    [
                        'default' => 1,
                        'sm' => 2,
                        'md' => 3
                    ]
                )->schema([
                    Select::make(name: 'key')
                        ->hiddenLabel()
                        ->placeholder(
                            fn () => 'Pilih ' . $this->stat['nama']
                        )
                        ->multiple()
                        ->live()
                        ->searchable()
                        ->options(
                            function () {
                                switch ($this->stat['nama']) {
                                    case 'Agama':
                                        return AgamaType::class;
                                    case 'Pendidikan':
                                        return PendidikanType::class;
                                    case 'Pekerjaan':
                                        return PekerjaanType::class;
                                    default:
                                        return [];
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

                ]),
                Toggle::make('tampilkan_null')
                    ->label('Tampilkan Nilai Kosong')
                    ->onIcon('fas-check')
                    ->offIcon('fas-times')
                    ->onColor('success')
                    ->offColor('danger')
                    ->inline()
                    ->live()
                    ->default(false),
                ToggleButtons::make('chart_type')
                    ->label('Tipe Chart')
                    ->hiddenLabel()
                    ->inline()
                    ->grouped()
                    ->reactive()
                    ->live()
                    ->visible(
                        function () {
                            if ($this->activeTab === 'tab2') {
                                return true;
                            }
                        }
                    )
                    ->options([
                        'bar' => 'Bar Chart',
                        'pie' => 'Pie Chart',
                    ])
                    ->colors([
                        'bar' => 'info',
                        'pie' => 'warning',
                    ])
                    ->default('bar')
            ]);
    }

    protected function getEloquentQuery(): Builder
    {
        $activeFilters = $this->filters['children_id'] ?? $this->filters['parent_id'] ?? null;

        return PendudukView::getView(key: $this->stat->key, wilayahId: $activeFilters)
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
            );
    }

    protected function getChartData(): array
    {
        return $this->getEloquentQuery()->get()->toArray();
    }
}