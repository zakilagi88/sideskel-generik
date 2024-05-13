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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

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

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return true;
    }

    public function hideIndexPage(): bool
    {
        return !str_contains($this->pageClass, 'Filament');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(
                fn (Penduduk $record): string => "{$record->nama_lengkap} - ({$record->nik} - {$record->wilayah->wilayah_nama})"
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap'),
                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->badge(),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->hidden($this->hideIndexPage())
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->with('wilayah'))
                    ->recordSelectSearchColumns(['nama_lengkap', 'nik'])
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
                Tables\Actions\EditAction::make()
                    ->hidden($this->hideIndexPage()),
                Tables\Actions\DeleteAction::make()
                    ->hidden($this->hideIndexPage()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])
                    ->hidden($this->hideIndexPage()),
            ])
            ->deferLoading(false);
    }
}
