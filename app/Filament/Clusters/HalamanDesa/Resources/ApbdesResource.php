<?php

namespace App\Filament\Clusters\HalamanDesa\Resources;

use App\Filament\Clusters\HalamanDesa;
use App\Filament\Clusters\HalamanDesa\Resources\ApbdesResource\Pages;
use App\Filament\Clusters\HalamanDesa\Resources\ApbdesResource\RelationManagers;
use App\Models\Apbdes;
use Coolsam\FilamentFlatpickr\Enums\FlatpickrTheme;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApbdesResource extends Resource
{
    protected static ?string $model = Apbdes::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = HalamanDesa::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Flatpickr::make('tahun')
                    ->label('Tahun')
                    ->placeholder('Pilih Tahun')
                    ->animate()
                    ->allowInput(true)
                    ->clickOpens(true)
                    ->theme(FlatpickrTheme::MATERIAL_BLUE)
                    ->required(),
                Forms\Components\TextInput::make('komponen')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('komponen_id')
                    ->searchable()
                    ->options(
                        fn () => Apbdes::with('parent')->pluck('komponen', 'komponen_id')
                    ),
                Forms\Components\TextInput::make('nilai')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('realisasi')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('komponen')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bidang')
                    ->searchable(),
                Tables\Columns\TextColumn::make('komponen_1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('komponen_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nilai')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('realisasi')
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApbdes::route('/'),
            'create' => Pages\CreateApbdes::route('/create'),
            'edit' => Pages\EditApbdes::route('/{record}/edit'),
        ];
    }
}
