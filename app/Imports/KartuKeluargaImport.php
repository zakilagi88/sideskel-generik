<?php

namespace App\Imports;

use App\Facades\Deskel;
use App\Models\AnggotaKeluarga;
use App\Models\DeskelProfil;
use App\Models\Dusun;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
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

class KartuKeluargaImport implements ToCollection, WithHeadingRow
{

    private $wilayah, $deskel;

    public function __construct()
    {

        $this->deskel = Deskel::getFacadeRoot();
        $this->wilayah = Wilayah::tree()->get();

        switch ($this->deskel->struktur) {
            case 'Khusus':
                $this->wilayah = $this->wilayah->where('depth', 0)->pluck('wilayah_nama', 'wilayah_id')->toArray();
                break;
            case 'Dasar':
                $this->wilayah = $this->wilayah->where('depth', 1)->pluck('wilayah_nama', 'wilayah_id')->toArray();
                break;
            case 'Lengkap':
                $this->wilayah = $this->wilayah->where('depth', 2)->pluck('wilayah_nama', 'wilayah_id')->toArray();
                break;
            default:
                $this->wilayah = [];
                break;
        }
    }

    public function concatWilayah($parent, $sub_parent = null, $child = null): string
    {
        switch ($this->deskel->struktur) {
            case 'Khusus':
                return $parent;
                break;
            case 'Dasar':
                return $child . ' / ' . $parent;
                break;
            case 'Lengkap':
                return $child . ' / ' . $sub_parent . ' / ' . $parent;
                break;
            default:
                return null;
                break;
        }
    }

    public function findWilayahId($concatenated)
    {
        $wilayah_id = array_search($concatenated, $this->wilayah);
        return $wilayah_id !== false ? $wilayah_id : null;
    }


    public function collection(Collection $rows)
    {

        $kartuKeluarga = collect();
        $penduduk = collect();

        foreach ($rows as $row) {

            switch ($this->deskel->struktur) {
                case 'Khusus':
                    $concatenated = $this->concatWilayah(parent: $row['dusun']);
                    break;
                case 'Dasar':
                    $concatenated = $this->concatWilayah(parent: $row['rw'], child: $row['rt']);
                    break;
                case 'Lengkap':
                    $concatenated = $this->concatWilayah(parent: $row['dusun'], sub_parent: $row['rw'], child: $row['rt']);
                    break;
                default:
                    $concatenated = null;
                    break;
            }

            if ($row['status_hubungan'] === 'KEPALA KELUARGA') {
                $kkData = [
                    'kk_id' => (string)$row['nomor_kk'],
                    'kk_alamat' => (string)$row['alamat_sekarang'],
                    'wilayah_id' => $this->findWilayahId($concatenated),
                    'created_at' => self::formatTanggal($row['tanggal_update_data']),
                    'updated_at' => self::formatTanggal($row['tanggal_update_data']),
                ];
                $kartuKeluarga->push($kkData);
            }

            $penduduk->push([
                'nik' => (string) $row['nik'],
                'is_nik_sementara' => (empty($row['nik_sementara']) || $row['nik_sementara'] == '-') ? false : true,
                'jenis_identitas' => $row['jenis_identitas'] ?? 'KTP',
                'kk_id' => (string) $row['nomor_kk'],
                'nama_lengkap' => $row['nama_lengkap'],
                'jenis_kelamin' => $row['jenis_kelamin'] ?? null,
                'tempat_lahir' => $row['tempat_lahir'] ?? null,
                'tanggal_lahir' => self::formatTanggal($row['tanggal_lahir']) ?? null,
                'umur' => Carbon::parse(self::formatTanggal($row['tanggal_lahir']))->age ?: null,
                'agama' => $row['agama'] ?? null,
                'pendidikan' => $row['pendidikan'] ?? null,
                'pekerjaan' => $row['pekerjaan'] ?? null,
                'kewarganegaraan' => $row['kewarganegaraan'] ?? 'WNI',
                'nama_ayah' => $row['nama_ayah'] ?? null,
                'nama_ibu' => $row['nama_ibu'] ?? null,
                'nik_ayah' => $row['nik_ayah'] ?? null,
                'nik_ibu' => $row['nik_ibu'] ?? null,
                'etnis_suku' => $row['etnis_suku'] ?? null,
                'golongan_darah' => $row['golongan_darah'] ?? null,
                'status_penduduk' => $row['status_penduduk'] ?? 'TETAP',
                'status_dasar' => $row['status_dasar'] ?? 'HIDUP',
                'status_perkawinan' => $row['status_perkawinan'],
                'status_hubungan' => $row['status_hubungan'],
                'alamat_sekarang' => $row['alamat_sekarang'],
                'alamat_sebelumnya' => $row['alamat_sebelumnya'] ?? null,
                'telepon' => $row['telepon'] ?? null,
                'email' => $row['email'] ?? null,
                'created_at' => self::formatTanggal($row['tanggal_update_data']),
                'updated_at' => self::formatTanggal($row['tanggal_update_data']),
            ]);
        }

        try {
            DB::beginTransaction();

            KartuKeluarga::disableAuditing();
            Penduduk::disableAuditing();

            KartuKeluarga::query()->getConnection()->statement('SET FOREIGN_KEY_CHECKS=0;');

            $chunksKeluarga = $kartuKeluarga->chunk(500);
            $chunksPenduduk = $penduduk->chunk(1000);

            foreach ($chunksKeluarga as $chunk) {
                KartuKeluarga::insert($chunk->toArray());
            }

            foreach ($chunksPenduduk as $chunk) {
                Penduduk::insert($chunk->toArray());
            }

            KartuKeluarga::query()->getConnection()->statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            dd($e->getMessage());
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
}
