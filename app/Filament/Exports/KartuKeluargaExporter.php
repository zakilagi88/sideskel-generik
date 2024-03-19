<?php

namespace App\Filament\Exports;

use App\Facades\Deskel;
use App\Models\KartuKeluarga;
use App\Models\Wilayah;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class KartuKeluargaExporter extends Exporter
{
    // protected static ?string $model = KartuKeluarga::class;

    public static function getFirstWord($string)
    {
        return explode(' ', trim($string))[0];
    }

    public static function exampleData(): array
    {
        $wilayah = Wilayah::tree()->get();
        $type = Deskel::getFacadeRoot()->deskel_tipe;
        $data = [
            'kk_id' => '1111111111000001',
            'kk_alamat' => 'Jl. Raya Kedungwuni',
        ];

        switch ($type) {
            case 'Khusus':
                $nama_1 = self::getFirstWord($wilayah->where('depth', 0)->pluck('wilayah_nama')->first());
                $data['wilayah_1'] = $nama_1 . ' Indonesia';
                break;
            case 'Dasar':
                $nama_1 = self::getFirstWord($wilayah->where('depth', 0)->pluck('wilayah_nama')->first());
                $nama_2 = self::getFirstWord($wilayah->where('depth', 1)->pluck('wilayah_nama')->first());
                $data['wilayah_1'] = $nama_1 . ' 001';
                $data['wilayah_2'] = $nama_2 . ' 001';
                break;
            case 'Lengkap':
                $nama_1 = self::getFirstWord($wilayah->where('depth', 0)->pluck('wilayah_nama')->first());
                $nama_2 = self::getFirstWord($wilayah->where('depth', 1)->pluck('wilayah_nama')->first());
                $nama_3 = self::getFirstWord($wilayah->where('depth', 2)->pluck('wilayah_nama')->first());
                $data[$nama_1] = $nama_1 . ' Indonesia';
                $data[$nama_2] = $nama_2 . ' 001';
                $data[$nama_3] = $nama_3 . ' 001';
                break;
        }

        return $data;
    }

    public static function getColumns(): array
    {
        $exampleData = static::exampleData();

        return [
            ExportColumn::make('kk_id')->state(fn () => $exampleData['kk_id']),
            ExportColumn::make('kk_alamat')->state(fn () => $exampleData['kk_alamat']),
            ExportColumn::make('wilayah_1')->label(label: self::getFirstWord($exampleData['wilayah_1']))->state(fn () => $exampleData['wilayah_1']),
            ExportColumn::make('wilayah_2')->state(fn () => $exampleData['wilayah_2'] ?? null),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your kependudukan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
