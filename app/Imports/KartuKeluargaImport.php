<?php

namespace App\Imports;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class KartuKeluargaImport implements ToCollection, WithHeadingRow, WithChunkReading
{

    private $wilayah;

    public function __construct()
    {
        $this->wilayah = Wilayah::all(['wilayah_id', 'wilayah_nama'])->pluck('wilayah_id', 'wilayah_nama');
    }

    public function collection(Collection $rows)
    {
        $kartuKeluarga = [];
        $penduduk = [];
        $kkdiproccess = [];


        $rows->each(
            function ($row) use (&$kartuKeluarga, &$penduduk, &$kkdiproccess) {

                $kkKey = array_search($row['nomor_kk'], array_column($kartuKeluarga, 'kk_id'));

                if ($kkKey === false) {
                    $kk = [
                        'kk_id' => (string)$row['nomor_kk'],
                        'kk_alamat' => (string)$row['alamat'],
                        'kk_kepala' => null,
                        'wilayah_id' => $this->wilayah[$row['wilayah']] ?? null,
                        'updated_at' => self::formatTanggal($row['tanggal_update_data']),
                    ];

                    if ($row['status_hubungan'] === 'KEPALA KELUARGA') {
                        $kk['kk_kepala'] = $row['nik'];
                    }

                    $kartuKeluarga[] = $kk;

                    $kkdiproccess[] = $row['nomor_kk'];
                } else {
                    if ($kartuKeluarga[$kkKey]['kk_kepala'] === null && $row['status_hubungan'] === 'KEPALA KELUARGA') {
                        $kartuKeluarga[$kkKey]['kk_kepala'] = $row['nik'];
                    }
                }


                $penduduk[] =
                    [
                        'nik' => $row['nik'],
                        'kk_id' => $row['nomor_kk'],
                        'nama_lengkap' => $row['nama_lengkap'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'tempat_lahir' => $row['tempat_lahir'],
                        'tanggal_lahir' => self::formatTanggal($row['tanggal_lahir']),
                        'agama' => $row['agama'],
                        'pendidikan' => $row['pendidikan'],
                        'pekerjaan' => $row['pekerjaan'],
                        'status_perkawinan' => $row['status_perkawinan'],
                        'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                        'ayah' => $row['ayah'] ?? null,
                        'ibu' => $row['ibu'] ?? null,
                        'golongan_darah' => $row['golongan_darah'] ?? null,
                        'status' => $row['status'],
                        'status_tempat_tinggal' => $row['status_tempat_tinggal'] ?? null,
                        'status_hubungan' => $row['status_hubungan'],
                        'etnis_suku' => $row['etnis_suku'] ?? null,
                        'alamat' => $row['alamat'],
                        'alamatKK' => $row['alamat_sesuai_kk'] === $row['alamat'],
                        'telepon' => $row['telepon'] ?? null,
                        'email' => $row['email'] ?? null,
                        'wilayah_id' => $this->wilayah[$row['wilayah']] ?? null,
                        'updated_at' => self::formatTanggal($row['tanggal_update_data']),
                    ];
            }



        );

        try {
            DB::beginTransaction();

            KartuKeluarga::disableAuditing();
            Penduduk::disableAuditing();

            $chunksKeluarga = array_chunk($kartuKeluarga, 1000);
            $chunksPenduduk = array_chunk($penduduk, 2000);

            foreach (array_values($chunksKeluarga) as $chunk) {
                KartuKeluarga::insert($chunk);
            }

            foreach ($chunksPenduduk as $chunk) {
                Penduduk::insert($chunk);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        } finally {
            KartuKeluarga::enableAuditing();
            Penduduk::enableAuditing();
        }
    }

    private function formatTanggal($tgl)
    {
        $unixTimestamp = Date::excelToTimestamp($tgl);
        $carbonDate = Carbon::createFromTimestamp($unixTimestamp, 'UTC');
        $formattedDate = $carbonDate->format('Y-m-d H:i:s');

        return $formattedDate;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}