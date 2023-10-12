<?php

namespace App\Filament\Resources\PendudukResource\Pages;

use App\Filament\Resources\PendudukResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use pxlrbt\FilamentExcel\Exports\ExcelExport;

class ViewPenduduk extends ViewRecord
{
    protected static string $resource = PendudukResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')->action(fn () => redirect()->route('filament.admin.resources.penduduk.index'))->button(),
            Actions\EditAction::make(),
            ExportAction::make()->exports([
                ExcelExport::make('form')->fromForm()->askForFilename()
                    ->askForWriterType(),
            ])
        ];
    }
}
