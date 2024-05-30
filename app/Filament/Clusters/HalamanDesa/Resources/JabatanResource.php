<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\JabatanResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\JabatanResource\RelationManagers;
use App\Models\Deskel\Jabatan;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class JabatanResource extends Resource
{
    protected static ?string $model = Jabatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = HalamanDesa::class;

    protected static ?string $slug = 'jabatan';

    protected static bool $shouldRegisterNavigation = false;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama')
                    ->required()
                    ->placeholder('Masukkan Nama Jabatan')
                    ->maxLength(100),
                Textarea::make('tupoksi')
                    ->placeholder('Masukkan Tupoksi Jabatan')
                    ->autosize(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tupoksi')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button()->iconSize(IconSize::Small),
                Tables\Actions\DeleteAction::make()->button()->iconSize(IconSize::Small),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJabatans::route('/'),
        ];
    }
}
