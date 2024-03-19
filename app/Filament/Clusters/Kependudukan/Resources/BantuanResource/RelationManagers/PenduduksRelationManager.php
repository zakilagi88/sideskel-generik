<?php

namespace App\Filament\Clusters\Kependudukan\Resources\BantuanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\ActionSize;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Table;
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
        return $table
            ->recordTitleAttribute('nama_lengkap')
            ->heading('Data Penduduk Terdaftar Bantuan')
            ->columns([
                TextColumn::make('nik')->label('NIK'),
                TextColumn::make('nama_lengkap')->label('Nama'),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('agama')->label('Agama'),
                TextColumn::make('status_pernikahan')->label('Status Pernikahan')
                    ->placeholder('Belum Diiisi'),
                TextColumn::make('pekerjaan')->label('Pekerjaan'),
                TextColumn::make('alamat')->label('Alamat'),
                TextColumn::make('status')->label('Status'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()->label('Tambahkan Data Terpilih')
                    ->color('success')
                    ->preloadRecordSelect(),
            ])
            ->actions([
                DetachAction::make()
                    ->label('Tidak Valid')
                    ->color('danger')
                    ->size(ActionSize::ExtraSmall)
                    ->requiresConfirmation()
                    ->modalHeading('Apakah Anda Yakin?')
                    ->modalDescription('Data yang tidak valid akan dihapus dari daftar bantuan.')
                    ->button(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DetachBulkAction::make(),
                ]),
            ]);
    }


    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->bantuan_sasaran == 'Penduduk';
    }
}
