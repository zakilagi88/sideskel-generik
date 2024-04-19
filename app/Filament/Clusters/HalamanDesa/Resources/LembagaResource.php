<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\RelationManagers;
use App\Models\Lembaga;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LembagaResource extends Resource
{
    protected static ?string $model = Lembaga::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = HalamanDesa::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('singkatan')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('dokumen_id')
                    ->relationship('dokumen', 'dok_nama'),
                Forms\Components\Repeater::make('kategori_jabatan')
                    ->label('Kategori Jabatan')
                    ->defaultItems(1)
                    ->simple(
                        TextInput::make('jabatan')
                            ->label('Nama Jabatan')
                            ->required()
                            ->maxLength(255),
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->alignLeft()
                    ->searchable(),
                Tables\Columns\TextColumn::make('singkatan')
                    ->alignCenter()
                    ->searchable(),
                Tables\Columns\TextColumn::make('anggota_count')
                    ->searchable()
                    ->alignCenter()
                    ->label('Jumlah Anggota')
                    ->counts('anggota'),
                Tables\Columns\TextColumn::make('alamat')
                    ->alignJustify()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dokumen.dok_nama')
                    ->alignJustify()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            RelationManagers\AnggotaRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLembagas::route('/'),
            'create' => Pages\CreateLembaga::route('/create'),
            'edit' => Pages\EditLembaga::route('/{record}/edit'),
        ];
    }
}
