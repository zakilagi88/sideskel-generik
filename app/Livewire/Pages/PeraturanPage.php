<?php

namespace App\Livewire\Pages;

use App\Filament\Clusters\HalamanArsip\Resources\PeraturanResource;
use App\Livewire\Templates\TablePage;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Model;

class PeraturanPage extends TablePage
{
    protected static string $resource = PeraturanResource::class;

    protected static string $heading = 'Peraturan Desa';

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Tables\Actions\Action::make('Preview File')
                ->label('Lihat')
                    ->button()
                    ->modalContent(function (Model $record) {
                        return view('filament.pages.preview-file', ['record' => $record]);
                    })
                    ->modalSubmitAction(false)
                    ->color('info')
                    ->icon('fas-eye')
                    ->iconSize(IconSize::Small),
            ], ActionsPosition::AfterColumns);
    }
}
