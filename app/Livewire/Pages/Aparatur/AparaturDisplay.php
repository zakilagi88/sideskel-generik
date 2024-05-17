<?php

namespace App\Livewire\Pages\Aparatur;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Livewire\Templates\SimplePage;
use App\Models\Desa\Aparatur;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class AparaturDisplay extends SimplePage
{
    protected static string $resource = AparaturResource::class;

    protected static string $heading = 'Aparatur';

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
                            Components\ImageEntry::make('foto')
                                ->hiddenLabel()
                                ->defaultImageUrl(
                                    fn (Aparatur $record) => strtolower($record->jenis_kelamin) === 'laki-laki' ? url('/images/user-man.png') : url('/images/user-woman.png')
                                )->extraAttributes(['class' => 'justify-center'])
                                ->size(240),
                        ])->columnSpan(['md' => 1, 'lg' => 1, 'xl' => 1, '2xl' => 2]),
                    Components\Section::make('Aparatur')
                        ->heading('Informasi Aparatur')
                        ->schema([
                            Components\TextEntry::make('nama')
                                ->inlineLabel()
                                ->extraAttributes($extraStyle),
                            Components\TextEntry::make('jenis_kelamin')
                                ->inlineLabel()
                                ->extraAttributes($extraStyle),
                            Components\TextEntry::make('pendidikan')
                                ->inlineLabel()
                                ->label('Pendidikan Terakhir')
                                ->extraAttributes($extraStyle),
                            Components\TextEntry::make('jabatan.nama')
                                ->inlineLabel()
                                ->label('Jabatan')
                                ->extraAttributes($extraStyle),
                        ])->columnSpan(['md' => 2, 'lg' => 2, 'xl' => 3, '2xl' => 3]),
                    Components\Section::make('Deskripsi')
                        ->heading('Deskripsi Tugas Pokok dan Fungsi (Tupoksi)')
                        ->schema([
                            Components\TextEntry::make('jabatan.tupoksi')
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
