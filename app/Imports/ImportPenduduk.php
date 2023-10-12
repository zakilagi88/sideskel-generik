<?php

namespace App\Imports;

use App\Models\Penduduk;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use DateTime;


class ImportPenduduk implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $dateString = $row['tanggal_lahir'];

        // Use Carbon to parse the date string with the correct format
        $carbonDate = Carbon::createFromFormat('d/m/Y', $dateString);

        // Convert Carbon date to a DateTimeInterface object
        $dateTime = $carbonDate->toDateTime();

        return new Penduduk([
            'nik' => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $dateTime,
            'agama' => $row['agama'],
            'pendidikan' => $row['pendidikan'],
            'pekerjaan' => $row['pekerjaan'],
            'status_pernikahan' => $row['status_pernikahan'],
            'status' => $row['status'],
            'alamat' => $row['alamat'],
            'alamatKK' => true,
        ]);
    }
}
