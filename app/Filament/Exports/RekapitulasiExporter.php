<?php

namespace App\Filament\Exports;

use App\Models\Rekapitulasi;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RekapitulasiExporter extends Exporter
{
    protected static ?string $model = Rekapitulasi::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('Perincian'),
            ExportColumn::make('WNA_lk')->label('WNA Laki-laki'),
            ExportColumn::make('WNA_pr')->label('WNA Perempuan'),
            ExportColumn::make('WNA_total')->label('WNA Total'),
            ExportColumn::make('WNI_lk')->label('WNI Laki-laki'),
            ExportColumn::make('WNI_pr')->label('WNI Perempuan'),
            ExportColumn::make('WNI_total')->label('WNI Total'),
            ExportColumn::make('Total')->label('Total')
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Rekapitulasi Data Mutasi Penduduk Telah Selesai di Unduh dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil di Unduh.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal di Unduh.';
        }

        return $body;
    }
}
