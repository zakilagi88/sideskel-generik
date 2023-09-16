<?php

namespace App\Filament\Resources\KartukeluargaResource\RelationManagers;

use Filament\Forms;
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
            ->schema([
                TextInput::make('nik')
                    ->default(random_int(1000000000000000, 9999999999999999))
                    ->label('NIK')
                    ->disabled()
                    ->dehydrated()
                    ->required(),
                TextInput::make('nama_lengkap')->required()
                    ->maxLength(255),
                TextInput::make('tempat_lahir')->required()
                    ->maxLength(255),
                // TextInput::make('tanggal_lahir')->required()
                //     ->maxLength(255),
                Select::make('jenis_kelamin')->options([
                    'L' => 'Laki-Laki',
                    'P' => 'Perempuan',
                ])

            ]);
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
                TextColumn::make('golongan_darah')->label('Golongan Darah'),
                TextColumn::make('status_pernikahan')->label('Status Pernikahan'),
                TextColumn::make('status_hubungan_dalam_keluarga')->label('Status Hubungan'),
                TextColumn::make('pekerjaan')->label('Pekerjaan'),
                TextColumn::make('alamat')->label('Alamat'),
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
