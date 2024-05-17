<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\AparaturResource;
use App\Livewire\Templates\TablePage;
use App\Models\Desa\Aparatur;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Model;

class AparaturPage extends TablePage
{
    protected static string $resource = AparaturResource::class;

    protected static string $heading = 'Aparatur Kepala Desa';

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->columns([
                Stack::make([
                    ImageColumn::make('foto')
                        ->alignCenter()
                        ->size(120)
                        ->checkFileExistence(false)
                        ->defaultImageUrl(
                            fn (Aparatur $record) => strtolower($record->jenis_kelamin) === 'laki-laki' ? url('/images/user-man.png') : url('/images/user-woman.png')
                        ),
                    TextColumn::make('nama')
                        ->weight(FontWeight::Bold)
                        ->size(TextColumnSize::Large)
                        ->alignment(Alignment::Center)
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('jabatan.nama')
                        ->prefix('Jabatan: ')
                        ->alignment(Alignment::Center)
                        ->size(TextColumnSize::Large)
                        ->weight(FontWeight::SemiBold)
                        ->color('primary'),
                ])
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->recordUrl(
                fn (Model $record): string => route('index.aparatur.show', ['record' => $record]),
            )
            ->selectable(false)
            ->actions([], ActionsPosition::AfterColumns);
    }
}