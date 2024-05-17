<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource;
use App\Livewire\Templates\TablePage;
use Filament\Infolists\Infolist;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Model;

class LembagaPage extends TablePage
{
    protected static string $resource = LembagaResource::class;

    protected static string $heading = 'Lembaga Desa';

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->selectable(false)
            ->recordUrl(
                fn (Model $record): string => route('index.lembaga.show', ['record' => $record]),
            )
            ->actions([], ActionsPosition::AfterColumns);
    }
}