<?php

namespace App\Imports;

// use Maatwebsite\Excel\Concerns\ToArray;

use App\Models\Penduduk;
use Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PendudukImport implements ToModel, HasReferencesToOtherSheets, WithHeadingRow
{
    public function model(array $row)
    {
        // Logika impor data Penduduk dari sheet kedua
        return new Penduduk([
            'nik' => $row['nik'], // Kolom NIK
            'kk_no' =>  $row['kk_no'], //
            'nama_lengkap' => $row['nama_lengkap'], // Kolom Nama Lengkap
            'jenis_kelamin' => $row['jenis_kelamin'], // Sesuaikan dengan kolom di Excel untuk jenis_kelamin
            'tempat_lahir' => $row['tempat_lahir'], // Sesuaikan dengan kolom di Excel untuk tempat_lahir
            'tanggal_lahir' => $row['tanggal_lahir'], // Sesuaikan dengan kolom di Excel untuk tanggal_lahir
            'agama' => $row['agama'], // Sesuaikan dengan kolom di Excel untuk agama
            'pendidikan' => $row['pendidikan'], // Sesuaikan dengan kolom di Excel untuk pendidikan
            'pekerjaan' => $row['pekerjaan'], // Sesuaikan dengan kolom di Excel untuk pekerjaan
            'golongan_darah' => $row['golongan_darah'], // Sesuaikan dengan kolom di Excel untuk golongan_darah
            'status_pernikahan' => $row['status_pernikahan'], // Sesuaikan dengan kolom di Excel untuk status_pernikahan
            'status_hubungan_dalam_keluarga' => $row['status_hubungan_dalam_keluarga'], // Sesuaikan dengan kolom di Excel untuk status_hubungan_dalam_keluarga
        ]);
    }
}
