<?php

namespace App\Filament\Clusters\Kesehatan\Resources;

use App\Filament\Clusters\Kesehatan\Resources\KesehatanAnakResource\Pages;
use App\Filament\Clusters\Kesehatan\HalamanKesehatan;
use App\Models\KesehatanAnak;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KesehatanAnakResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = KesehatanAnak::class;

    protected static ?string $navigationIcon = 'fas-baby';

    protected static ?string $cluster = HalamanKesehatan::class;

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kategori.indeks')
                    ->required()
                    ->placeholder('Kategori')
                    ->numeric(),
                Forms\Components\TextInput::make('subkategori.subkategori_nama')
                    ->required()
                    ->placeholder('Subkategori')
                    ->numeric(),
                Forms\Components\TextInput::make('anak.nama_lengkap')
                    ->placeholder('Nama Anak')
                    ->maxLength(16),
                Forms\Components\TextInput::make('ibu.nama_lengkap')
                    ->placeholder('Nama Ibu')
                    ->maxLength(16),
                Forms\Components\TextInput::make('berat_badan')
                    ->placeholder('Berat Badan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('tinggi_badan')
                    ->placeholder('Tinggi Badan')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('indeks_massa_tubuh')
                    ->placeholder('Indeks Massa Tubuh')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori.indeks')
                    ->placeholder('Kategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subkategori.subkategori_nama')
                    ->placeholder('Subkategori')
                    ->sortable(),
                Tables\Columns\TextColumn::make('anak.nama_lengkap')
                    ->placeholder('Nama Anak')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ibu.nama_lengkap')
                    ->placeholder('Nama Ibu')
                    ->searchable(),
                Tables\Columns\TextColumn::make('berat_badan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tinggi_badan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('indeks_massa_tubuh')
                    ->numeric()
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKesehatanAnaks::route('/'),
            'create' => Pages\CreateKesehatanAnak::route('/create'),
            'edit' => Pages\EditKesehatanAnak::route('/{record}/edit'),
        ];
    }
}
