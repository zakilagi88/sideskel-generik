<?php

namespace App\Filament\Clusters\HalamanDesa\Resources\LembagaResource\RelationManagers;

use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnggotaRelationManager extends RelationManager
{
    protected static string $relationship = 'anggota';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nik')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('jabatan')
                    ->options(
                        function () {
                            $kategoris = $this->getOwnerRecord()->kategori_jabatan;
                            $modifiedKeyValue = [];
                            foreach ($kategoris as $kategori) {
                                $normalizedKey = str_replace(' ', '-', strtolower($kategori));
                                $modifiedKeyValue[$normalizedKey] = $kategori;
                            }
                            return $modifiedKeyValue;
                        }
                    )
                    ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan')
                    ->dehydrateStateUsing(fn (string $state): string => ucwords($state))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nik')
            ->columns([
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Keterangan')
                    ->badge(),
                Tables\Columns\TextColumn::make('keterangan')->label('Keterangan')->badge(),
                Tables\Columns\TextColumn::make('nama_lengkap')->label('Nama Lengkap'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->recordSelect(
                        function (Select $select) {
                            return $select->options(
                                fn () => Penduduk::query()
                                    ->with('kartuKeluarga.wilayahs')
                                    ->get()
                                    ->sortBy(function ($penduduk) {
                                        return optional($penduduk->kartuKeluarga)->wilayah_id;
                                    })
                                    ->map(fn ($penduduk) => [
                                        'value' => $penduduk->nik,
                                        'label' => $penduduk->nik . ' - ' . $penduduk->nama_lengkap . ' - ' . optional($penduduk->kartuKeluarga->wilayahs)->wilayah_nama,
                                    ])->pluck('label', 'value')
                            );
                        }
                    )
                    ->form(fn (AttachAction $action): array => [
                        Forms\Components\Select::make('jabatan')
                            ->options(
                                function () {
                                    $kategoris = $this->getOwnerRecord()->kategori_jabatan;
                                    $modifiedKeyValue = [];
                                    foreach ($kategoris as $kategori) {
                                        $normalizedKey = str_replace(' ', '-', strtolower($kategori));
                                        $modifiedKeyValue[$normalizedKey] = $kategori;
                                    }
                                    return $modifiedKeyValue;
                                }
                            )
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                        Forms\Components\Select::make('pilihan')
                            ->live()
                            ->label('Pilihan Pemilihan')
                            ->options([
                                'one' => 'Satu Anggota',
                                'multiple' => 'Beberapa Anggota',
                            ])
                            ->afterStateUpdated(fn (Select $component) => $component
                                ->getContainer()
                                ->getComponent('fieldList')
                                ->getChildComponentContainer()
                                ->fill()),
                        Forms\Components\Grid::make(1)
                            ->schema(fn (Get $get): array => match ($get('pilihan')) {
                                'one' => [
                                    $action->getRecordSelect()
                                ],
                                'multiple' => [
                                    $action->getRecordSelect()->multiple(),
                                ],
                                default => [],
                            })
                            ->key('fieldList'),
                        Forms\Components\TextInput::make('keterangan')
                            ->label('Keterangan')
                            ->required()
                            ->dehydrateStateUsing(fn (string $state): string => ucwords($state)),
                    ]),
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
}
