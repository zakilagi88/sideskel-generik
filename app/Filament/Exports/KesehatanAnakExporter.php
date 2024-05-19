<?php

namespace App\Filament\Exports;

use App\Models\KesehatanAnak;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;

class KesehatanAnakExporter extends Exporter
{
    protected static ?string $model = KesehatanAnak::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('anak.nik')->label('NIK Anak'),
            ExportColumn::make('anak.nama_lengkap')->label('Nama Anak'),
            ExportColumn::make('anak.wilayah.wilayah_nama')->label('Wilayah'),
            ExportColumn::make('anak.nama_ibu')->label('Nama Ibu'),
            ExportColumn::make('berat_badan'),
            ExportColumn::make('tinggi_badan'),
            ExportColumn::make('imt'),
            ExportColumn::make('kategori_tbu'),
            ExportColumn::make('kategori_bbu'),
            ExportColumn::make('kategori_imtu'),
            ExportColumn::make('kategori_tb_bb'),
            ExportColumn::make('z_score_tbu')->enabledByDefault(false),
            ExportColumn::make('z_score_bbu')->enabledByDefault(false),
            ExportColumn::make('z_score_imtu')->enabledByDefault(false),
            ExportColumn::make('z_score_tb_bb')->enabledByDefault(false),
            ExportColumn::make('created_at'),
            ExportColumn::make('updated_at'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Data Kesehatan Anak telah berhasil' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' gagal di ekspor.';
        }

        return $body;
    }
}
