<?php

namespace App\Imports;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ImportGabung implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new KartuKeluargaImport(),
            new PendudukImport(),
        ];
    }
}
