<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanPotensi\Resources\PotensiSDAResource;
use Livewire\Component;
use App\Livewire\Templates\TablePage;
use App\Models\Desa\PotensiSDA;
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

class PotensiPage extends TablePage
{
    protected static string $resource = PotensiSDAResource::class;

    protected static string $heading = 'Potensi Sumber Data Alam';

    protected static bool $isClusterParent = true;


    public function table(Table $table): Table
    {
        return parent::table($table)
            ->recordUrl(
                fn (Model $record): string => route('index.potensi.sda.show', ['record' => $record]),
            )
            ->selectable(false)
            ->actions([], ActionsPosition::AfterColumns);
    }
}
