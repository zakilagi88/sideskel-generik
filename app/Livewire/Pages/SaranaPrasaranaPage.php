<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\SaranaPrasaranaResource;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class SaranaPrasaranaPage extends Component
{
    protected static string $resource = SaranaPrasaranaResource::class;

    protected static string $heading = 'Sarana Prasarana Desa/Kelurahan';

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