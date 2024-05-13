<?php

namespace App\Livewire\Pages\Lembaga;

use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Livewire\Templates\SimplePage;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Testing\Fluent\Concerns\Interaction;

class LembagaDisplay extends SimplePage
{

    protected static string $resource = LembagaResource::class;

    protected static string $heading = 'Lembaga';

    public function infolist(Infolist $infolist): Infolist
    {
        if (!$this->record) {
            return $infolist;
        }

        $extraStyle = fn (?string $state) => is_null($state)
            ? [] : ['class' => 'border-solid border-gray-400 pb-1 dark:border-gray-600 border-b hover:bg-gray-100'];
        return $infolist
            ->record($this->record)
            ->schema([
                Components\Grid::make([
                    'default' => 1,
                    'sm' => 2,
                    'md' => 3,
                    'lg' => 3,
                    'xl' => 4,
                    '2xl' => 5,
                ])->schema([
                    Components\Section::make('')
                        ->schema([
                            Components\ImageEntry::make('logo_url')
                                ->hiddenLabel()
                                ->defaultImageUrl($this->record->getLogoUrl())
                                ->extraAttributes(['class' => 'justify-center'])
                                ->size(240),
                        ])->columnSpan(['md' => 1, 'lg' => 1, 'xl' => 1, '2xl' => 2]),
                    Components\Section::make('Lembaga')
                        ->heading('Informasi Lembaga')
                        ->schema([
                            Components\TextEntry::make('nama')
                                ->inlineLabel()
                                ->extraAttributes($extraStyle),
                            Components\TextEntry::make('singkatan')
                                ->inlineLabel()
                                ->extraAttributes($extraStyle),
                            Components\TextEntry::make('dokumen.dok_nama')
                                ->inlineLabel()
                                ->label('Dasar Hukum Pembentukan')
                                ->extraAttributes($extraStyle),
                            Components\TextEntry::make('alamat')
                                ->inlineLabel()
                                ->label('Alamat')
                                ->extraAttributes($extraStyle),
                        ])->columnSpan(['md' => 2, 'lg' => 2, 'xl' => 3, '2xl' => 3]),
                    Components\Section::make('Deskripsi')
                        ->heading('Deskripsi Lembaga')
                        ->schema([
                            Components\TextEntry::make('deskripsi')
                                ->prose()
                                ->hiddenLabel()
                                ->extraAttributes($extraStyle),
                        ])->columnSpan(['md' => 3, 'lg' => 3, 'xl' => 4, '2xl' => 5]),

                ]),

            ]);
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