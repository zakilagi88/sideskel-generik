<?php

namespace App\Filament\Exports;

use App\Models\Penduduk\PendudukView as PendudukPendudukView;
use App\Models\PendudukView;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\Log;

class PendudukViewExporter extends Exporter
{
    protected static ?string $model = PendudukPendudukView::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('keterangan')
                ->state(function (PendudukPendudukView $record) {
                    //get custom key sbg kolom
                    $key = $record->custom_key;

                    return $record->{$key};
                }),
            ExportColumn::make('laki_laki'),
            ExportColumn::make('perempuan'),
            ExportColumn::make('total'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Statistik Data Penduduk Telah Selesai di Unduh dan  ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil di Unduh.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal di Unduh.';
        }

        return $body;
    }
}
