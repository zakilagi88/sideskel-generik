<?php

namespace App\Filament\Resources;

use App\Filament\Pages\PendudukStats;
use App\Filament\Resources\StatistikResource\Pages;
use App\Filament\Resources\StatistikResource\RelationManagers;
use App\Models\Statistik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatistikResource extends Resource
{
    protected static ?string $model = Statistik::class;

    protected static ?string $navigationIcon = 'fas-people-group';

    protected static ?string $navigationLabel = 'Statistik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()

                    ->label('Judul')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                // ->afterStateUpdated(
                //     function (string $operation, $state, Set $set) {
                //         if ($operation === 'create') {
                //             $set('judul', Str::title($state));
                //             $set('slug', Str::slug($state));
                //         }
                //     }
                // ),
                Forms\Components\TextInput::make('slug')
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->unique(Statistik::class, 'slug', ignoreRecord: true),
                Forms\Components\TextInput::make('heading_grafik')
                    ->maxLength(255),
                Forms\Components\TextInput::make('heading_tabel')
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsi_grafik')
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsi_tabel')
                    ->maxLength(255),
                Forms\Components\TextInput::make('path_grafik')
                    ->maxLength(255),
                Forms\Components\TextInput::make('path_tabel')
                    ->maxLength(255),
                Forms\Components\Toggle::make('tampilkan_grafik')
                    ->required(),
                Forms\Components\Toggle::make('tampilkan_tabel')
                    ->required(),
                Forms\Components\TextInput::make('jenis_grafik')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('heading_grafik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('heading_tabel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi_grafik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi_tabel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('path_grafik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('path_tabel')
                    ->searchable(),
                Tables\Columns\IconColumn::make('tampilkan_grafik')
                    ->boolean(),
                Tables\Columns\IconColumn::make('tampilkan_tabel')
                    ->boolean(),
                Tables\Columns\TextColumn::make('jenis_grafik')
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ManageStatistiks::route('/'),

        ];
    }
}