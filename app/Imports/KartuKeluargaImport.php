<?php

namespace App\Imports;

use App\Facades\Deskel;
use App\Jobs\InsertsJob;
use App\Jobs\NotifyJob;
use App\Models\Wilayah;
use App\Settings\GeneralSettings;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Bus;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class KartuKeluargaImport implements ToCollection, WithHeadingRow
{

    private $wilayah, $deskel, $settings;

    public function __construct()
    {

        $this->deskel = Deskel::getFacadeRoot();
        $this->wilayah = Wilayah::tree()->get();
        $this->settings = app(GeneralSettings::class)->toArray();

        $depth = match ($this->deskel->struktur) {
            'Khusus' => 0,
            'Dasar' => 1,
            'Lengkap' => 2,
            default => null,
        };

        $this->wilayah = $depth !== null
            ? $this->wilayah->where('depth', $depth)->pluck('wilayah_nama', 'wilayah_id')->toArray()
            : [];
    }

    public function concatWilayah($parent, $sub_parent = null, $child = null): string
    {
        return match ($this->deskel->struktur) {
            'Khusus' => $this->settings['sebutan_wilayah']['Khusus'][0] . ' ' . $parent,
            'Dasar' => $this->settings['sebutan_wilayah']['Dasar'][1] . ' ' . $child . ' / ' . $this->settings['sebutan_wilayah']['Dasar'][0] . ' ' . $parent,
            'Lengkap' => $this->settings['sebutan_wilayah']['Lengkap'][2] . ' ' . $child . ' / ' . $this->settings['sebutan_wilayah']['Lengkap'][1] . ' ' . $sub_parent . ' / ' . $this->settings['sebutan_wilayah']['Lengkap'][0] . ' ' . $parent,
            default => null,
        };
    }

    public function findWilayahId($concatenated)
    {
        return array_search($concatenated, $this->wilayah) ?: null;
    }


    public function collection(Collection $rows)
    {

        $kartuKeluarga = collect();
        $penduduk = collect();

        foreach ($rows as $row) {

            $concatenated = match ($this->deskel->struktur) {
                'Khusus' => $this->concatWilayah(parent: $row[strtolower($this->settings['sebutan_wilayah']['Khusus'][0])]),
                'Dasar' => $this->concatWilayah(parent: $row[strtolower($this->settings['sebutan_wilayah']['Dasar'][0])], child: $row[strtolower($this->settings['sebutan_wilayah']['Dasar'][1])]),
                'Lengkap' => $this->concatWilayah(parent: $row[strtolower($this->settings['sebutan_wilayah']['Lengkap'][0])], sub_parent: $row[strtolower($this->settings['sebutan_wilayah']['Lengkap'][1])], child: $row[strtolower($this->settings['sebutan_wilayah']['Lengkap'][2])]),
                default => null,
            };

            $isKkExist = $kartuKeluarga->where('kk_id', $row['nomor_kk'])->first();

            if (!$isKkExist) {
                $kkData = [
                    'kk_id' => (string)$row['nomor_kk'],
                    'kk_alamat' => (string)$row['alamat_sekarang'],
                    'wilayah_id' => $this->findWilayahId($concatenated),
                    'created_at' => self::formatTanggal($row['tanggal_update_data']),
                    'updated_at' => self::formatTanggal($row['tanggal_update_data']),
                ];
                $kartuKeluarga->push($kkData);
            } else {
                $kkData = $isKkExist;
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
                'status_pengajuan' => 'DIVERIFIKASI',
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

        $kkChunks = $kartuKeluarga->chunk(500);
        $pddChunks = $penduduk->chunk(1000);

        /** @var \App\Models\User */
        $authUser = Filament::auth()->user()->id;

        InsertsJob::dispatch(kkChunks: $kkChunks, pddChunks: $pddChunks, authUser: $authUser);
    }

    private function formatTanggal($tgl)
    {
        $unixTimestamp = Date::excelToTimestamp($tgl);
        $carbonDate = Carbon::createFromTimestamp($unixTimestamp, 'UTC');
        $formattedDate = $carbonDate->format('Y-m-d H:i:s');
        return $formattedDate;
    }
}