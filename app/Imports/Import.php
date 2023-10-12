<?php

namespace App\Imports;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\SLS;
use Carbon\Carbon;
use DateTime;
// use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class Import implements WithMultipleSheets

{
    public function sheets(): array
    {
        return [
            0 => new KartuKeluargaImport(),
        ];
    }
}
