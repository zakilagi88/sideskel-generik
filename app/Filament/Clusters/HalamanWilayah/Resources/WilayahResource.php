<?php

namespace App\Filament\Clusters\HalamanWilayah\Resources;

use App\Facades\Deskel;
use App\Filament\Clusters\HalamanWilayah;
use App\Filament\Clusters\HalamanWilayah\Resources\WilayahResource\Pages;
use App\Models\DeskelProfil;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Filament\Forms\Components\{Group, Hidden, Select, TextInput};
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class WilayahResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

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
                Hidden::make('alamat')->default(
                    fn () => $deskelProfile->alamat ?? null
                ),
                Select::make('parent_id')
                    ->searchable()
                    ->live()
                    ->native(false)
                    ->options(
                        fn () => Wilayah::with('parent')->pluck('wilayah_nama', 'wilayah_id')
                    ),
                TextInput::make('wilayah_nama')
                    ->required()
                    ->maxLength(100),
                TextInput::make('tingkatan')
                    ->readOnly()
                    ->required(
                        function (Get $get, Set $set) {
                            $parent = $get('parent_id');
                            if ($parent == null) {
                                $set('tingkatan', 0);
                            } else {
                                $level = Wilayah::tree()->find($parent)->depth;

                                $set('tingkatan', $level + 1);
                            }
                        }
                    ),
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
            ->query(
                function () {
                    return (Wilayah::tree()
                        ->orderByRaw("CAST(SUBSTRING_INDEX(path, '.', 1) AS UNSIGNED), 
                                        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(path, '.', 2), '.', -1) AS UNSIGNED),
                                        CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(path, '.', 3), '.', -1) AS UNSIGNED)")
                    );
                }

            )
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('parent.wilayah_nama')
                    ->getStateUsing(
                        function (Wilayah $record) {
                            if ($record->tingkatan !== "0") {
                                return null;
                            }
                            return $record->bloodline()->where('tingkatan', 0)->pluck('wilayah_nama');
                        }
                    )
                    ->label('RW')
                    ->listWithLineBreaks()
                    ->searchable(),
                Tables\Columns\TextColumn::make('subparent.wilayah_nama')
                    ->getStateUsing(
                        function (Wilayah $record) {
                            if ($record->tingkatan === "0") {
                                return null;
                            } else {
                                return $record->childrenAndSelf()->where('tingkatan', 1)->pluck('wilayah_nama');
                            }
                        }
                    )
                    ->label('RT')
                    ->searchable(),
                Tables\Columns\TextColumn::make('children.wilayah_nama')
                    ->getStateUsing(
                        function (Wilayah $record) {
                            if ($record->tingkatan === "0" | $record->tingkatan === '1') {
                                return null;
                            } else {
                                return $record->childrenAndSelf()->where('tingkatan', 2)->pluck('wilayah_nama');
                            }
                        }
                    )
                    ->hidden(
                        fn () =>
                        Deskel::getFacadeRoot()->where('struktur', 'Dasar')

                    )
                    ->label('RT')
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
