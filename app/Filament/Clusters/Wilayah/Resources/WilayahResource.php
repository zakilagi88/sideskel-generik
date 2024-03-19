<?php

namespace App\Filament\Clusters\Wilayah\Resources;

use App\Facades\Deskel;
use App\Filament\Clusters\Wilayah\HalamanWilayah;
use App\Filament\Clusters\Wilayah\Resources\WilayahResource\Pages;
use App\Models\DeskelProfil;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Filament\Forms\Components\{Group, Hidden, Select, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WilayahResource extends Resource
{
    protected static ?string $model = Wilayah::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $cluster = HalamanWilayah::class;

    public static function form(Form $form): Form
    {
        $deskelProfile = Deskel::getFacadeRoot();

        return $form
            ->schema([
                Hidden::make('deskel_id')->default(
                    fn () => $deskelProfile->deskel_id ?? null
                ),
                Hidden::make('deskel_alamat')->default(
                    fn () => $deskelProfile->deskel_alamat ?? null
                ),
                Select::make('parent_id')
                    ->searchable()
                    ->native(false)
                    ->options(
                        fn () => Wilayah::with('parent')->pluck('wilayah_nama', 'wilayah_id')
                    ),
                TextInput::make('wilayah_nama')
                    ->maxLength(100),
                Select::make('wilayah_kepala')
                    ->relationship('kepalaWilayah')
                    ->searchable()
                    ->options(
                        fn () => Penduduk::query()
                            ->get()
                            ->sortBy(function ($penduduk) {
                                return $penduduk->kartuKeluarga ? $penduduk->kartuKeluarga->wilayah_id : PHP_INT_MAX;
                            })
                            ->map(function ($penduduk) {
                                $label = $penduduk->nik . ' - ' . $penduduk->nama_lengkap;
                                if ($penduduk->kartuKeluarga && $penduduk->kartuKeluarga->wilayahs) {
                                    $label .= ' - ' . $penduduk->kartuKeluarga->wilayahs->wilayah_nama;
                                }
                                return [
                                    'value' => $penduduk->nik,
                                    'label' => $label,
                                ];
                            })
                            ->pluck('label', 'value')
                    ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('deskel.deskel_nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('wilayah_nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kepalaWilayah.nama_lengkap')
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
            'index' => Pages\ListWilayahs::route('/'),
            'create' => Pages\CreateWilayah::route('/create'),
            'edit' => Pages\EditWilayah::route('/{record}/edit'),
        ];
    }
}
