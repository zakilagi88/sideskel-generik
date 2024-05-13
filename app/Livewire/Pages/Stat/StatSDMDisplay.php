<?php

namespace App\Livewire\Pages\Stat;

use App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource;
use App\Filament\Clusters\HalamanKependudukan\Resources\TambahanResource;
use App\Filament\Clusters\HalamanStatistik\Resources\StatSDMResource;
use App\Livewire\Templates\SimplePage;
use App\Livewire\Widgets\Tables\StatSDMTable;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use App\Models\StatKategori;
use App\Models\Tambahan;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Support\Enums\IconPosition;
use Illuminate\Http\Request;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Support\Str;

class StatSDMDisplay extends SimplePage
{
    protected static string $resource = StatSDMResource::class;

    protected static string $heading = 'Statistik Penduduk';

    protected static string $parameter = 'stat';


    public ?array $filters = [];
    public ?array $komponen = [];
    public $activeTab;

    public function mount(int | string $record): void
    {
        $req = app(Request::class);
        /** @var Request $req */
        $tambahan = $req->routeIs('index.stat.tambahan.show');
        $bantuan = $req->routeIs('index.stat.bantuan.show');

        if ($tambahan) {
            $this->extraResources()['tambahan'];
            $this->record = $this->resolveRecord($record, 'tambahan');
        } elseif ($bantuan) {
            $this->extraResources()['bantuan'];
            $this->record = $this->resolveRecord($record, 'tambahan');
        } else {
            parent::mount($record, $req);
        }
    }

    #[Computed()]
    public function extraResources(): array
    {
        return [
            'tambahan' => TambahanResource::class,
            'bantuan' => BantuanResource::class
        ];
    }

    #[Computed()]
    public function kategori()
    {
        $statKategori = StatKategori::all(['id', 'nama'])->load(['stats' => fn ($query) => $query->aktif()]);
        $tambahan = Tambahan::aktif()->get(['nama', 'id', 'slug']);

        return $statKategori
            ->flatMap(function ($item) {
                return [$item->nama => $item];
            })->merge(['Statistik Tambahan' => $tambahan]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        if (!$this->record) {
            return $infolist;
        }

        $extraStyle = fn (?string $state) => is_null($state)
            ? [] : ['class' => 'border-solid border-gray-400 pb-1 dark:border-gray-600 border-b hover:bg-gray-100'];
        return $infolist
            ->record($this->record)
            ->schema(
                [
                    Components\Grid::make([
                        'default' => 1,
                        'sm' => 4,
                        'md' => 4,
                        'lg' => 4,
                        'xl' => 5,
                        '2xl' => 5,
                    ])
                        ->schema([
                            Components\Group::make()
                                ->schema($this->sideBar()),
                            Components\Group::make()
                                ->schema([

                                    Livewire::make(StatSDMWidgets::class)
                                        ->columnSpanFull()
                                        ->hiddenLabel(),

                                ])
                                ->columnSpan(['sm' => 3, 'md' => 3, 'lg' => 3, 'xl' => 4, '2xl' => 4]),

                        ]),
                ]
            );
    }

    public function sideBar(): array
    {
        $accordion = [];

        foreach ($this->kategori as $key => $value) {

            $snaked = Str::snake($key);
            if ($value instanceof StatKategori) {
                $accordion[] = Components\Section::make($key)
                    ->key($snaked . $value->id)
                    ->extraAttributes(['class' => 'fi-section-sidebar'])
                    ->collapsible()
                    ->columnStart(1)
                    ->columnSpan(['sm' => 1, 'md' => 1, 'lg' => 1, 'xl' => 2, '2xl' => 2])
                    ->schema(
                        function () use ($value, $snaked) {
                            $komponen = [];
                            foreach ($value->stats as $stat) {
                                $komponen[] = ViewEntry::make($snaked . $stat->id)
                                    ->id($snaked . $value->id)
                                    ->key($stat->slug)
                                    ->label($stat->nama)
                                    ->view('infolists.components.accordion-item')
                                    ->hiddenLabel();
                            }
                            return $komponen;
                        }
                    );
            } else {
                $accordion[] = Components\Section::make($key)
                    ->key($snaked)
                    ->extraAttributes(['class' => 'fi-section-sidebar'])
                    ->collapsible()
                    ->columnStart(1)
                    ->columnSpan(['sm' => 1, 'md' => 1, 'lg' => 1, 'xl' => 2, '2xl' => 2])
                    ->schema(
                        function () use ($value, $snaked) {
                            $komponen = [];
                            foreach ($value as $stat) {
                                $komponen[] = ViewEntry::make($snaked . $stat->id)
                                    ->id($snaked)
                                    ->key($stat->slug)
                                    ->label($stat->nama)
                                    ->view('infolists.components.accordion-item')
                                    ->hiddenLabel();
                            }
                            return $komponen;
                        }
                    );
            }
        }

        return $accordion;
    }

    protected function getPageHeading(): string
    {
        return $this->record->nama ?? static::$heading;
    }

    public function render()
    {
        return view('livewire.templates.infolist-page');
    }
}
