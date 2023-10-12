<?php

namespace App\Filament\Resources\KartukeluargaResource\RelationManagers;

use App\Enum\Penduduk\Agama;
use App\Enum\Penduduk\JenisKelamin;
use App\Enum\Penduduk\Pekerjaan;
use App\Enum\Penduduk\Pendidikan;
use App\Enum\Penduduk\Pengajuan;
use App\Enum\Penduduk\Pernikahan;
use App\Enum\Penduduk\Status;
use App\Models\Penduduk;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PenduduksRelationManager extends RelationManager
{
    protected static string $relationship = 'penduduks';


    // protected static ?string $recordTitleAttribute = 'nik';

    public function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                    Group::make()
                        ->schema([
                            Section::make()
                                ->heading('Informasi Penduduk')
                                ->description('Silahkan isi data penduduk dengan benar')
                                ->schema([
                                    TextInput::make('nik')
                                        ->label('NIK')
                                        ->unique(ignoreRecord: true)
                                        ->required(),
                                    TextInput::make('nama_lengkap')
                                        ->label('Nama Lengkap')
                                        ->required(),
                                    Group::make()
                                        ->label('Jenis Kelamin')
                                        ->schema([
                                            Select::make('agama')
                                                ->options(Agama::class)
                                                ->required(),
                                            Select::make('jenis_kelamin')
                                                ->options(JenisKelamin::class)
                                                ->required(),
                                        ])->columns(2),
                                    Group::make()
                                        ->label('Tempat dan Tanggal Lahir')
                                        ->schema([
                                            TextInput::make('tempat_lahir')
                                                ->label('Tempat Lahir')
                                                ->required(),
                                            DatePicker::make('tanggal_lahir')
                                                ->label('Tanggal Lahir')
                                                ->required(),
                                        ])->columns(2),
                                ]),

                            Section::make()
                                ->heading('Informasi Tambahan')
                                ->description('Silahkan isi data tambahan penduduk')
                                ->schema([
                                    Select::make('pendidikan')
                                        ->label('Pendidikan')
                                        ->options(Pendidikan::class),
                                    Select::make('status_pernikahan')
                                        ->label('Status Pernikahan')
                                        ->options(Pernikahan::class),
                                    Select::make('pekerjaan')
                                        ->label('Pekerjaan')
                                        ->options(Pekerjaan::class)
                                        ->searchingMessage('Mencari Jenis Pekerjaan')
                                        ->searchable()
                                        ->required(),
                                ])->collapsible(),
                        ])->columnSpan(['lg' => 2]),
                    Group::make()
                        ->schema([
                            Group::make()
                                ->schema([
                                    Section::make()
                                        ->schema(
                                            [
                                                Placeholder::make('created_at')
                                                    ->label('Dibuat Pada')
                                                    ->content(fn (Penduduk $record): ?string => ($record->created_at?->diffForHumans()))
                                                    ->disabledOn('create'),
                                                Placeholder::make('updated_at')
                                                    ->label('Diubah Pada')
                                                    ->content(function (Penduduk $record) {
                                                        if ($record->audits()->count() > 0) {
                                                            $latestAudit = $record->audits()->latest()->first();
                                                            $userName = $latestAudit->user->name;
                                                            $timeDiff = $latestAudit->updated_at->diffForHumans();

                                                            return $timeDiff . ' oleh ' . $userName;
                                                        } else {
                                                            return 'Belum ada yang mengubah';
                                                        }
                                                    })
                                                    ->disabledOn('create')
                                            ]
                                        )->hidden(fn (?Penduduk $record) => $record === null),
                                    Section::make()
                                        ->heading('Data Lainnya')
                                        ->description('Silahkan Isi Ada')
                                        ->schema([
                                            Select::make('kesehatan')
                                                ->preload()
                                                ->relationship('kesehatan', 'kesehatan_jaminan')
                                                ->multiple()

                                                ->searchingMessage('Mencari Jaminan Kesehatan')
                                                ->createOptionForm(
                                                    [
                                                        TextInput::make('kesehatan_jaminan')
                                                            ->label('Jaminan Kesehatan')
                                                    ]
                                                ),
                                            Select::make('bantuan')
                                                ->label('Bantuan')
                                        ])->collapsible(),
                                ]),
                            Section::make()
                                ->heading('Status Tempat Tinggal')
                                ->description('Keterangan Status Tempat Tinggal')
                                ->schema(
                                    [
                                        Select::make('status')
                                            ->options(Status::class)
                                            ->required(),
                                        TextInput::make('alamat')
                                            ->label('Alamat')
                                            ->required(),
                                    ]
                                ),
                            Section::make()
                                ->heading('Status Pengajuan')
                                ->description('Keterangan Status Pengajuan')
                                ->schema(
                                    [
                                        Select::make('status_pengajuan')
                                            ->options(Pengajuan::class)
                                            ->disabledOn(['create', 'edit'])

                                            ->required(),
                                    ]
                                ),

                        ])->columnSpan(['lg' => 1]),
                ]
            )->columns(3);
    }


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kk_no')
            ->columns([
                TextColumn::make('nik')->label('NIK'),
                TextColumn::make('nama_lengkap')->label('Nama'),
                TextColumn::make('tempat_lahir')->label('Tempat Lahir'),
                TextColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
                TextColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
                TextColumn::make('agama')->label('Agama'),
                TextColumn::make('status_pernikahan')->label('Status Pernikahan'),
                TextColumn::make('pekerjaan')->label('Pekerjaan'),
                TextColumn::make('alamat')->label('Alamat'),
                TextColumn::make('status')->label('Status'),
                TextColumn::make('status_pengajuan')->label('Status Pengajuan'),
                TextColumn::make('anggotaKeluarga.hubungan')->label('Hubungan Keluarga'),
            ])
            ->filters([
                SelectFilter::make('jenis_kelamin')->multiple()->options(
                    [
                        'L' => 'Laki-Laki',
                        'P' => 'Perempuan',
                    ]
                ),
                SelectFilter::make('agama')->multiple()->options(
                    [
                        'Islam' => 'Islam',
                        'Kristen' => 'Kristen Protestan',
                        'Katolik' => 'Kristen Katolik',
                        'Hindu' => 'Hindu',
                        'Budha' => 'Budha',
                        'Konghucu' => 'Konghucu',

                    ]
                ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }
}
