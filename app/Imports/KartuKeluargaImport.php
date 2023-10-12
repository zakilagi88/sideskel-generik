<?php

namespace App\Imports;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\SLS;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KartuKeluargaImport implements ToModel, WithHeadingRow
{

    public function model(array $row)

    {

        $nokk = $row['no_kk'];

        $tglupdate = Date::excelToTimestamp($row['tanggal_update_data']);
        $format_tglupdate = $this->formatTanggal($tglupdate);

        $kartuKeluarga = $this->findOrCreateKK($nokk, $row);

        $penduduk = $this->CreatePenduduk($row, $format_tglupdate);
        $anggotaKeluarga = $this->CreateAnggotaKeluarga($row, $format_tglupdate);

        if ($row['hubungan'] === 'KEPALA KELUARGA') {
            $this->UpdateKepalaKK($row, $format_tglupdate);
        }

        return [
            $penduduk,
            $kartuKeluarga,
            $anggotaKeluarga,
        ];
    }

    private function formatTanggal($tgl)
    {
        $date = new DateTime("@$tgl");
        return $date->format('Y-m-d H:i:s');
    }

    private function findOrCreateKK($nokk, $row)
    {
        $kartuKeluarga = KartuKeluarga::where('kk_id', (string) $nokk)->first();

        if (!$kartuKeluarga) {
            $tglupdate = $this->formatTanggal(Date::excelToTimestamp($row['tanggal_update_data']));

            $dataToInsert = [
                'kk_id' => (string) $nokk,
                'kk_alamat' => (string) $row['alamat'],
                'kk_kepala' => null,
                'sls_id' => (string) (SLS::where('sls_nama', (string) $row['wilayah'])->pluck('sls_id')->first()),
                'updated_at' => $tglupdate,
            ];

            $kartuKeluarga = KartuKeluarga::create($dataToInsert);
        }

        return $kartuKeluarga;
    }

    private function CreatePenduduk($row, $format_tglupdate)
    {
        $tgl = Date::excelToTimestamp($row['tanggal_lahir']);
        $format_tgl = $this->formatTanggal($tgl);

        $penduduk = Penduduk::create([
            'nik' => $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $format_tgl,
            'agama' => $row['agama'],
            'pendidikan' => $row['pendidikan'],
            'pekerjaan' => $row['pekerjaan'],
            'status_pernikahan' => $row['status_pernikahan'],
            'status' => $row['status'],
            'alamat' => $row['alamat'],
            'alamatKK' => $row['alamat_sesuai_kk'] === $row['alamat'] ? true : false,
            'status_pengajuan' => 'SELESAI',
            'updated_at' => $format_tglupdate,
        ]);

        return $penduduk;
    }

    private function CreateAnggotaKeluarga($row, $format_tglupdate)
    {
        $anggotaKeluarga = AnggotaKeluarga::create([
            'nik' => $row['nik'],
            'kk_id' => $row['no_kk'],
            'hubungan' => $row['hubungan'],
            'updated_at' => $format_tglupdate,
        ]);

        return $anggotaKeluarga;
    }

    private function UpdateKepalaKK($row, $format_tglupdate)
    {
        $kartuKeluarga = KartuKeluarga::where('kk_id', (string) $row['no_kk'])->first();
        $kartuKeluarga->kk_kepala = $row['nik'];
        $kartuKeluarga->updated_at = $format_tglupdate;
        $kartuKeluarga->save();
    }

    // public function batchSize(): int
    // {
    //     return 500;
    // }
}
