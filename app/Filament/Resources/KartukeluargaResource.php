<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartukeluargaResource\Pages;
use App\Filament\Resources\KartukeluargaResource\RelationManagers;
use App\Filament\Resources\KartukeluargaResource\RelationManagers\PenduduksRelationManager;
use App\Models\Kartukeluarga;
// use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KartukeluargaResource extends Resource
{
    protected static ?string $model = Kartukeluarga::class;

    protected static ?string $recordTitleAttribute = 'kk_no';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Kartu Keluarga';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('kk_no')
                            ->label('Nomor Kartu Keluarga'),
                        Textarea::make('kk_alamat')
                            ->label('Alamat')
                            ->required(),
                        TextInput::make('rt_id')
                            ->label('RT')
                            ->required(),
                        TextInput::make('rw_id')
                            ->label('RW')
                            ->required(),

                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('kk_no')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kk_alamat')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rt_id')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('rw_id')
                    ->searchable()
                    ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])

            ->headerActions([
                CreateAction::make()->label('Kartu Keluarga Baru'),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PenduduksRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartukeluargas::route('/'),
            'create' => Pages\CreateKartukeluarga::route('/create'),
            'edit' => Pages\EditKartukeluarga::route('/{record}/edit'),
        ];
    }
}
