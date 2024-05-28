<?php

namespace App\Filament\Clusters\HalamanKependudukan\Resources\BantuanResource\RelationManagers;

use App\Filament\Clusters\HalamanKependudukan\Resources\PendudukResource;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PenduduksRelationManager extends RelationManager
{
    protected static string $relationship = 'penduduks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_lengkap')
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
            ->recordTitleAttribute('nama_lengkap')->recordTitle(
                fn (Penduduk $record): string => "{$record->nama_lengkap} - ({$record->wilayah?->wilayah_nama})"
            )
            ->modifyQueryUsing(
                fn (Builder $query) => $query->byWilayah($authUser, $descendants)
            )
            ->heading('Data Penduduk Terdaftar Bantuan')
            ->columns([
                TextColumn::make('no')->label('No')->alignCenter()->rowIndex(),
                TextColumn::make('nik')->label('NIK')->url(fn ($record) => PendudukResource::getUrl('edit', ['record' => $record->nik]))->color('primary'),
                TextColumn::make('wilayah.wilayah_nama')->label('Wilayah')->placeholder('Belum Diiisi'),
                TextColumn::make('nama_lengkap')->label('Nama Lengkap')->placeholder('Belum Diiisi'),
                TextColumn::make('alamat_sekarang')->label('Alamat')->placeholder('Belum Diiisi'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin')->placeholder('Belum Diiisi'),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir')->placeholder('Belum Diiisi'),
                TextColumn::make('umur')->sortable()->label('Usia')->suffix(' Tahun'),
                TextColumn::make('agama')->label('Agama')->placeholder('Belum Diiisi')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('pendidikan')->label('Pendidikan')->placeholder('Belum Diiisi')->toggleable(isToggledHiddenByDefault: true),

            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->recordSelectOptionsQuery(
                        fn (Builder $query) => $query->byWilayah($authUser, $descendants)
                    )
                    ->recordSelectSearchColumns(['nama_lengkap', 'nik'])
                    ->preloadRecordSelect()
                    ->multiple()
                    ->recordSelect(
                        fn (Select $select) => $select->placeholder('Pilih Penduduk...'),
                    )
                    ->label('Tambahkan Data Terpilih')
                    ->color('success')->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Tidak Valid')
                    ->color('danger')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar bantuan.')
                    ->button()->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
            ], ActionsPosition::BeforeColumns)
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make()->hidden(fn () => $authUser->hasRole('Monitor Wilayah')),
                ]),
            ]);
    }


    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->bantuan_sasaran == 'Penduduk';
    }
}