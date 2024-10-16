<?php

namespace App\Filament\Exports;

use App\Models\Deskel\SaranaPrasarana as DeskelSaranaPrasarana;
use App\Models\SaranaPrasarana;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SaranaPrasaranaExporter extends Exporter
{
    protected static ?string $model = DeskelSaranaPrasarana::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('jenis')->label('Jenis'),
            ExportColumn::make('data')->label('Data')



        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your sarana prasarana export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
