<?php

namespace App\Filament\Exports;

use App\Models\Penduduk;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PendudukExporter extends Exporter
{
    protected static ?string $model = Penduduk::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nik')->label('NIK'),
            ExportColumn::make('kk_id')->label('KK ID'),
            ExportColumn::make('jenis_identitas')->label('Jenis Identitas'),

            ExportColumn::make('nama_lengkap')->label('Nama Lengkap'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin')
                ->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('tempat_lahir')->label('Tempat Lahir'),
            ExportColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('umur')->label('Umur'),
            ExportColumn::make('agama')->label('Agama')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('pendidikan')->label('Pendidikan')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('pekerjaan')->label('Pekerjaan')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('status_perkawinan')->label('Status Perkawinan')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('tgl_perkawinan')->label('Tanggal Perkawinan'),
            ExportColumn::make('tgl_perceraian')->label('Tanggal Perceraian'),
            ExportColumn::make('kewarganegaraan')->label('Kewarganegaraan')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('nama_ayah')->enabledByDefault(false),
            ExportColumn::make('nama_ibu')->enabledByDefault(false),
            ExportColumn::make('nik_ayah')->enabledByDefault(false),
            ExportColumn::make('nik_ibu')->enabledByDefault(false),
            ExportColumn::make('golongan_darah')->label('Golongan Darah')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('etnis_suku')->label('Etnis Suku')->formatStateUsing(fn ($state) => $state->value  ?? null),
            ExportColumn::make('cacat')->enabledByDefault(false),
            ExportColumn::make('penyakit')->enabledByDefault(false),
            ExportColumn::make('akseptor_kb')->enabledByDefault(false),
            ExportColumn::make('status_hubungan')->label('Status Hubungan')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('status_penduduk')->label('Status Penduduk'),
            ExportColumn::make('status_dasar')->label('Status Dasar')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('status_tempat_tinggal')->label('Status Tempat Tinggal')->formatStateUsing(fn ($state) => $state->value ?? null),
            ExportColumn::make('alamat_sekarang'),
            ExportColumn::make('alamat_sebelumnya'),
            ExportColumn::make('is_nik_sementara'),
            ExportColumn::make('telepon')->enabledByDefault(false),
            ExportColumn::make('email')->enabledByDefault(false),
            ExportColumn::make('status_identitas')->label('Status Identitas'),
            ExportColumn::make('status_rekam_identitas')->label('Status Rekam Identitas'),
            ExportColumn::make('foto')->label('Foto'),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your penduduk export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
