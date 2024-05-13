<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanDesa\Resources\KeputusanResource;
use App\Livewire\Templates\TablePage;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Model;

class KeputusanPage extends TablePage
{
    protected static string $resource = KeputusanResource::class;

    protected static string $heading = 'Keputusan Kepala Desa';

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\Action::make('Preview File')
                    ->hiddenLabel()
                    ->button()
                    ->modalContent(function (Model $record) {
                        return view('filament.pages.preview-file', ['record' => $record]);
                    })
                    ->modalSubmitAction(false)
                    ->color('success')
                    ->icon('fas-eye')
                    ->iconSize('md'),
            ], ActionsPosition::AfterColumns);
    }
}
