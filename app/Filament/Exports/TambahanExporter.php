<?php

namespace App\Filament\Exports;

use App\Models\Tambahan;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TambahanExporter extends Exporter
{
    protected static ?string $model = Tambahan::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('tambahans.nama')->label('Nama Data Tambahan'),
            ExportColumn::make('nik'),
            ExportColumn::make('nama_lengkap'),
            ExportColumn::make('alamat_sekarang'),
            ExportColumn::make('jenis_kelamin'),
            ExportColumn::make('tempat_lahir'),
            ExportColumn::make('tanggal_lahir'),
            ExportColumn::make('agama'),
            ExportColumn::make('pekerjaan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your tambahan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
