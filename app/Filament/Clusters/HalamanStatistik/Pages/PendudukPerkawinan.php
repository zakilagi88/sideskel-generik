<?php

namespace App\Filament\Clusters\HalamanStatistik\Pages;

use App\Enums\Kependudukan\PerkawinanType;
use App\Filament\Clusters\HalamanStatistik;
use App\Livewire\Widgets\{Charts\Penduduk\PerkawinanChart, Tables\Penduduk\PerkawinanTable};
use App\Models\{RT, RW, Statistik, Wilayah};
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\{Actions\Action, Group, Section, Select};
use Filament\Forms\{Form, Get, Set};
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;

class PendudukPerkawinan extends Page
{

    protected static ?string $navigationGroup = 'Statistik Penduduk';

    protected static ?string $cluster = HalamanStatistik::class;

    protected static string $view = 'filament.clusters.penduduk-stats.index';

    protected static ?string $slug = 'penduduk-perkawinan';

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
                                    $this->filters['status_perkawinan'] = [];
                                    $this->filters['parent_id'] = null;
                                    $this->filters['wilayah_id'] = null;

                                    return $this->filters;
                                }
                            ),
                    ])
                    ->schema([
                        Select::make('status_perkawinan')
                            ->label('Status Perkawinan')
                            ->placeholder('Pilih Status Perkawinan')
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->columnSpanFull()
                            ->options(
                                function () {
                                    return PerkawinanType::class;
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
                                        return Wilayah::where('parent_id', $get('parent_id') ?? null)->pluck('wilayah_nama', 'wilayah_id');
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
            PerkawinanTable::class,
            PerkawinanChart::class,
        ];
    }
}