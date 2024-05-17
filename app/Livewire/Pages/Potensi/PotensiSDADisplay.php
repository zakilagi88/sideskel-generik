<?php

namespace App\Livewire\Pages\Potensi;

use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use App\Infolists\Components\TableListEntry;
use Livewire\Component;
use App\Livewire\Templates\SimplePage;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Http\Request;

class PotensiSDADisplay extends SimplePage
{
    protected static string $resource = PotensiSDAResource::class;

    protected static string $heading = 'Potensi Sumber Daya Alam';

    protected static bool $isCluster = true;

    protected static string $parentSlug = 'potensi';

    protected static string $parameter = 'sda';

    public function infolist(Infolist $infolist): Infolist
    {
        if (!$this->record) {
            return $infolist;
        }

        $extraStyle = fn (?string $state) => is_null($state)
            ? [] : ['class' => 'border-solid border-gray-400 pb-1 dark:border-gray-600 border-b hover:bg-gray-100'];
        return $infolist
            ->state($this->mutateRecord($this->record->toArray()))
            ->schema([
                Components\Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 3,
                    'xl' => 4,
                    '2xl' => 5,
                ])->schema([
                    Components\Section::make('potensi')
                        ->heading('Informasi Potensi')
                        ->schema([
                            Components\TextEntry::make('jenis')
                                ->inlineLabel()
                                ->extraAttributes($extraStyle),
                        ])->columnSpan(['md' => 2, 'lg' => 2, 'xl' => 3, '2xl' => 3]),
                    Components\Section::make('Deskripsi')
                        ->heading('Deskripsi Lembaga')

                        ->schema([

                            Components\RepeatableEntry::make('data')
                                ->hiddenLabel()
                                ->extraAttributes([
                                    'class' => 'relative overflow-hidden'
                                ])
                                ->schema([
                                    TextEntry::make('extra')
                                        ->label(fn ($state) => $state['extra']['label'] ?? 'Extra')
                                        ->hidden(fn ($state) => !isset($state['extra']))
                                        ->inlineLabel()
                                        ->extraAttributes($extraStyle),
                                    TextEntry::make('label')
                                        ->label('Tabel')
                                        ->inlineLabel()
                                        ->extraAttributes($extraStyle),
                                    TableListEntry::make('entitas')
                                        ->extraAttributes([
                                            'class' => 'overflow-x-scroll'
                                        ])
                                        ->hiddenLabel()
                                ])
                                ->contained(false),
                        ])->columnSpan(['md' => 3, 'lg' => 3, 'xl' => 4, '2xl' => 5]),

                ]),

            ]);
    }

    protected function mutateRecord($record)
    {
        $mutated = collect($record['data']);

        $record['data'] = $mutated->map(function ($item) {
            $entitas = array_map(function ($row) {
                ksort($row);
                return array_map('strval', $row);
            }, $item['entitas']);

            $header = array_unique(array_merge(array_keys($entitas[0] ?? []), ...array_map(function ($entita) {
                return array_keys($entita);
            }, $entitas)));

            $header = array_map(function ($item) {
                return preg_replace('/^\d+\s/', '', ucwords(str_replace('_', ' ', $item)));
            }, $header);

            ksort($header);

            return [
                'extra' => $item['extra'] ?? [],
                'label' => $item['label'],
                'entitas' => array_merge([$header], $entitas),
            ];
        })->toArray();

        return $record;
    }

    protected function getRouteName(): string
    {
        $slug = $this->getPageSlug() ?: static::$resource::getSlug();

        // if ($req->routeIs('index.stat.show')) {
        //     return 'index.' . $slug;
        // } else {
        //     if (static::$isCluster) {
        //         if (static::$parentSlug) {
        //             return 'index.'  . static::$parentSlug . '.' . $this->currentResource::getSlug();
        //         } else {
        //             return 'index.' . $this->currentResource::getCluster()::getSlug() . '.' . $this->currentResource::getSlug();
        //         }
        //     }
        // }

        return 'index.' . static::$parentSlug . '.' . static::$parameter;
    }

    protected function getPageSlug(): string
    {
        return $this->record->jenis ?? $this->record->{$this->parameter};
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
