<?php

namespace App\Filament\Imports;

use App\Models\KartuKeluarga;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class KartuKeluargaImporter extends Importer
{
    protected static ?string $model = KartuKeluarga::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('kk_id')
                ->requiredMapping()
                ->rules(['required', 'string']),
            ImportColumn::make('kk_alamat')
                ->example('Jl. Jend. Sudirman No. 1')
        ];
    }

    public function resolveRecord(): ?KartuKeluarga
    {
        // return KartuKeluarga::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new KartuKeluarga();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your kartu keluarga import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
