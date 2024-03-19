<?php

namespace App\Filament\Clusters\Kependudukan\Resources\BantuanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\{AttachAction, BulkActionGroup, DetachAction, DetachBulkAction};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeluargasRelationManager extends RelationManager
{
    protected static string $relationship = 'keluargas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kk_kepala')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kk_kepala')
            ->heading('Data Keluarga Terdaftar Bantuan')
            ->columns([
                TextColumn::make('kk_kepala'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()->label('Tambahkan Data Terpilih')
                    ->color('success'),
            ])
            ->actions([
                DetachAction::make()->label('Tidak Valid')->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar bantuan.')
                    ->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->bantuan_sasaran == 'Keluarga';
    }
}
