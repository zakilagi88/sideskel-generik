<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriBeritaResource\Pages;
use App\Filament\Resources\KategoriBeritaResource\RelationManagers;
use App\Models\KategoriBerita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriBeritaResource extends Resource
{
    protected static ?string $model = KategoriBerita::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(KategoriBerita::class, 'slug', ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('beritas_count')
                    ->searchable()
                    ->label('Jumlah Artikel')
                    ->counts('beritas'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat pada')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)

                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui pada')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)

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
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKategoriBeritas::route('/'),
            'create' => Pages\CreateKategoriBerita::route('/create'),
            'edit' => Pages\EditKategoriBerita::route('/{record}/edit'),
        ];
    }
}