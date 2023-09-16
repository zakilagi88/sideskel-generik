<?php

namespace App\Imports;

// use Maatwebsite\Excel\Concerns\ToArray;

use App\Models\KartuKeluarga;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KartuKeluargaImport implements ToModel, WithCalculatedFormulas, WithHeadingRow
{
    public function model(array $row)
    {
        // Logika impor data Penduduk dari sheet kedua
        return new KartuKeluarga([
            'kk_no' => $row['kk_no'],
            'kk_alamat' => $row['kk_alamat'],
            'rt_id' => $row['rt_id'],
            'rw_id' => $row['rw_id'],
        ]);
    }
}
