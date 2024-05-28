<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\RelationManagers;

use App\Models\KartuKeluarga;
use App\Models\Wilayah;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\{AttachAction, BulkActionGroup, DetachAction, DetachBulkAction};
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KeluargasRelationManager extends RelationManager
{
    protected static string $relationship = 'keluargas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kk_kepala')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        /** @var \App\Models\User */
        $authUser = Filament::auth()->user();
        $descendants = ($authUser->hasRole('Monitor Wilayah')) ? Wilayah::tree()->find($authUser->wilayah_id)->descendants->pluck('wilayah_id') : null;
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query
                    ->byWilayah($authUser, $descendants)
            )
            ->recordTitle(
                fn (KartuKeluarga $record): string => "{$record->kepalaKeluarga?->nama_lengkap} - ({$record->kepalaKeluarga?->wilayah?->wilayah_nama})"
            )
            ->heading('Data Keluarga Terdaftar Bantuan')
            ->columns([
                TextColumn::make('no')->label('No')->alignCenter()->rowIndex(),
                TextColumn::make('tambahanable_ket')->label('Keterangan')->badge()->sortable()->alignJustify(),
                TextColumn::make('kepalaKeluarga.nama_lengkap')->label('Nama Kepala Keluarga'),
                TextColumn::make('kepalaKeluarga.nik')->label('NIK'),
                TextColumn::make('wilayah.wilayah_nama')->label('Wilayah'),
                TextColumn::make('kepalaKeluarga.alamat_sekarang')->label('Alamat'),
                TextColumn::make('kepalaKeluarga.jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('kepalaKeluarga.tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('kepalaKeluarga.umur')->sortable()->label('Usia')->suffix(' Tahun')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectOptionsQuery(
                        fn (Builder $query) => $query
                            ->with(['kepalaKeluarga', 'wilayah', 'kepalaKeluarga.wilayah'])
                            ->byWilayah($authUser, $descendants)
                            ->leftJoin('penduduk as p', 'kartu_keluarga.kk_id', '=', 'p.kk_id')
                            ->where('p.status_hubungan', 'KEPALA KELUARGA')
                    )
                    ->recordSelectSearchColumns(['p.nama_lengkap', 'p.nik'])
                    ->preloadRecordSelect()
                    ->label('Tambahkan Data Terpilih')
                    ->color('success')
                    ->multiple()
                    ->recordSelect(
                        fn (Select $select) => $select->placeholder('Pilih Keluarga...'),
                    )
                ->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
            ])
            ->actions([
                DetachAction::make()->label('Tidak Valid')->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar bantuan.')
                    ->button()
                ->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
                ]),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->bantuan_sasaran == 'Keluarga';
    }
}