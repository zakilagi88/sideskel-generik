<?php

namespace App\Filament\Clusters\Statistik\Pages;

use App\Enums\Kependudukan\PendidikanType;
use App\Filament\Clusters\Statistik\HalamanStatistik;
use App\Livewire\Widgets\{Charts\Penduduk\PendidikanChart, Tables\Penduduk\PendidikanTable};
use App\Models\{RT, RW, Statistik, Wilayah};
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\{Actions\Action, Group, Section, Select};
use Filament\Forms\{Form, Get, Set};
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class PendudukPendidikan extends Page
{

    protected static ?string $navigationGroup = 'Statistik Penduduk';

    protected static ?string $cluster = HalamanStatistik::class;

    protected static string $view = 'filament.clusters.penduduk-stats.index';

    protected static ?string $slug = 'penduduk-pendidikan';

    public $statistik;

    use HasFiltersForm, HasPageShield;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->id('filters-section')
                    ->headerActions([
                        Action::make('filter_reset')
                            ->label('Reset Filter')
                            ->color('gray')
                            ->button()
                            ->action(
                                function () {
                                    $this->filters['pendidikan'] = [];
                                    $this->filters['parent_id'] = null;
                                    $this->filters['wilayah_id'] = null;

                                    return $this->filters;
                                }
                            ),
                    ])
                    ->schema([
                        Select::make('pendidikan')
                            ->label('Pendidikan')
                            ->placeholder('Pilih Pendidikan')
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->columnSpanFull()
                            ->options(
                                function () {
                                    return PendidikanType::class;
                                }
                            ),
                        Group::make([
                            Select::make('parent_id')
                                ->label('wilayah')
                                ->placeholder('Pilih wilayah')
                                ->afterStateUpdated(
                                    function (Set $set, ?string $state) {
                                        $set('children_id', null);
                                    }
                                )
                                ->options(
                                    function () {
                                        return Wilayah::tree()->get()->where('depth', 0)->pluck('wilayah_nama', 'wilayah_id');
                                    }
                                ),
                            Select::make('children_id')
                                ->label('wilayah')
                                ->placeholder('Pilih wilayah')
                                ->options(
                                    function (Get $get) {
                                        return wilayah::where('parent_id', $get('parent_id') ?? null)->pluck('wilayah_nama', 'wilayah_id');
                                    }
                                ),
                        ])->columns(2)->columnSpanFull()

                    ])
                    ->columns(2),
            ]);
    }

    public function mount()
    {
        $this->statistik = Statistik::all();
    }


    public function getColumns(): int | string | array
    {
        return 1;
    }

    public function getVisibleWidgets(): array
    {
        return $this->filterVisibleWidgets($this->getWidgets());
    }

    protected function getWidgets(): array
    {
        return [
            PendidikanTable::class,
            PendidikanChart::class,
        ];
    }
}
