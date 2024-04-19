<?php

namespace App\Filament\Clusters\HalamanStatistik\Pages;

use App\Enums\Kependudukan\AgamaType;
use App\Filament\Clusters\HalamanStatistik;
use App\Livewire\Widgets\{Tables\Penduduk\AgamaTable, Charts\Penduduk\AgamaChart};
use App\Models\{RW, Stat, Statistik, Wilayah};
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms\Components\{Actions\Action, Group, Section, Select};
use Filament\Forms\{Form, Get, Set};
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Resources\Pages\PageRegistration;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Route as FacadesRoute;

class Kependudukan extends Page
{
    protected static ?string $navigationGroup = 'Statistik Kependudukan';

    // protected static ?string $cluster = HalamanStatistik::class;

    protected static string $view = 'livewire.stat.stat-display';

    protected static ?string $slug = 'ss ';

    public $stat;

    use HasFiltersForm, HasPageShield;

    // public function registerManyRoutes(Panel $panel): void
    // {
    //     $cek = Route::name('berita')
    //         ->prefix(static::prependClusterSlug(''))
    //         ->group(fn () => static::routes($panel));
    //     return $cek;
    // }

    // public static function getRoutePath(): string
    // {
    //     dd(parent::getRoutePath());
    // }

    protected function getViewData(): array
    {
        return [
            'stat' => $this->stat,
        ];
    }

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
                                    $this->filters['agama'] = [];
                                    $this->filters['parent_id'] = null;
                                    $this->filters['wilayah_id'] = null;

                                    return $this->filters;
                                }
                            ),
                    ])
                    ->schema([
                        Select::make(name: 'agama')
                            ->label('Agama')
                            ->placeholder('Pilih Agama')
                            ->searchable()
                            ->multiple()
                            ->live()
                            ->columnSpanFull()
                            ->options(
                                function () {
                                    return AgamaType::class;
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

    public function getFiltersForm(): Form
    {
        if ((!$this->isCachingForms) && $this->hasCachedForm('filtersForm')) {
            return $this->getForm('filtersForm');
        }

        return $this->filtersForm(
            $this->makeForm()
                ->columns([
                    'md' => 2,
                    'xl' => 3,
                    '2xl' => 4,
                ])
                ->statePath('filters')
                ->debounce(100)
        );
    }

    public function mount()
    {
        $this->stat = Stat::all();
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
            // AgamaTable::class,
            AgamaChart::class,
        ];
    }
}
