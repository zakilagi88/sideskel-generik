<?php

namespace App\Exports;

use App\Facades\Deskel;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date as SharedDate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TemplateImport implements FromCollection, WithHeadings, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    protected $deskel;
    protected $wilayah;

    public function __construct()
    {
        $this->deskel = Deskel::getFacadeRoot();
        $this->loadWilayah();
    }

    protected function loadWilayah()
    {
        $this->wilayah = Wilayah::tree()->get();
        // Cache data wilayah jika memungkinkan
        // Misalnya: cache()->rememberForever('wilayah', function () {
        //     return Wilayah::tree()->get();
        // });
    }

    private function getFirstWordFromWilayah($depth)
    {
        return explode(' ', trim($this->wilayah->where('depth', $depth)->pluck('wilayah_nama', 'wilayah_id')->first()))[0];
    }

    public function getExampleData(): array
    {
        $type = $this->deskel->deskel_tipe;

        $dataKK = [];

        $dataPdd = [
            [
                'nik' => '0111111111000012',
                'nama_lengkap' => 'FULAN',
                'jenis_kelamin' => 'LAKI-LAKI',
                'tempat_lahir' => 'DKI Jakarta',
                'tanggal_lahir' => Carbon::parse('1999-01-01')->format('d/m/Y'),
                'agama' => 'ISLAM',
                'pendidikan' => 'TAMAT SD/SEDERAJAT',
                'pekerjaan' => 'PELAJAR/MAHASISWA',
                'kewarganegaraan' => 'WNI',
                'status_penduduk' => 'TETAP',
                'status_dasar' => 'HIDUP',
                'status_perkawinan' => 'KAWIN',
                'status_hubungan' => 'KEPALA KELUARGA',
                'nama_ayah' => 'AYAHNYA FULAN',
                'nama_ibu' => 'IBUNYA FULAN',
                'nik_ayah' => '0111111111000001',
                'nik_ibu' => '0111111111000002',
                'etnis_suku' => 'JAWA',
                'golongan_darah' => 'A',
                'alamat_sekarang' => 'JL. INDONESIA RAYA',
                'alamat_sebelumnya' => 'JL. INDONESIA RAYA LAMA',
                'telepon' => '081234567890',
                'email' => 'fulan@fulan.id',
                'tanggal_update_data' => Carbon::now()->format('d/m/Y'),
            ],
            [
                'nik' => '0111111111000013',
                'nama_lengkap' => 'FULANA',
                'jenis_kelamin' => 'PEREMPUAN',
                'tempat_lahir' => 'DKI Jakarta',
                'tanggal_lahir' => Carbon::parse('1990-01-01')->format('d/m/Y'),
                'agama' => 'BUDDHA',
                'pendidikan' => 'TAMAT SD/SEDERAJAT',
                'pekerjaan' => 'MENGURUS RUMAH TANGGA',
                'kewarganegaraan' => 'WNI',
                'status_penduduk' => 'TETAP',
                'status_dasar' => 'HIDUP',
                'status_perkawinan' => 'BELUM KAWIN',
                'status_hubungan' => 'ISTRI',
                'nama_ayah' => 'AYAHNYA FULANA',
                'nama_ibu' => 'IBUNYA FULANA',
                'nik_ayah' => '0111111111000003',
                'nik_ibu' => '0111111111000004',
                'etnis_suku' => 'SUNDA',
                'golongan_darah' => 'B',
                'alamat_sekarang' => 'Jl. INDONESIA RAYA',
                'alamat_sebelumnya' => 'Jl. INDONESIA RAYA LAMA',
                'telepon' => '081234567890',
                'email' => 'fulana@fulana.id',
                'tanggal_update_data' => Carbon::now()->format('d/m/Y'),
            ]
        ];

        $result = [];
        foreach ($dataPdd as $pdd) {
            switch ($type) {
                case 'Khusus':
                    $nama_1 = $this->getFirstWordFromWilayah(0);
                    $dataKK[$nama_1] = $nama_1 . ' Indonesia';
                    $dataKK['kk_id'] = '0111111111000021';
                    break;
                case 'Dasar':
                    $nama_1 = $this->getFirstWordFromWilayah(0);
                    $nama_2 = $this->getFirstWordFromWilayah(1);
                    $dataKK[$nama_1] = $nama_1 . ' 001';
                    $dataKK[$nama_2] = $nama_2 . ' 001';
                    $dataKK['kk_id'] = '0111111111000021';
                    break;
                case 'Lengkap':
                    $nama_1 = $this->getFirstWordFromWilayah(0);
                    $nama_2 = $this->getFirstWordFromWilayah(1);
                    $nama_3 = $this->getFirstWordFromWilayah(2);
                    $dataKK[$nama_1] = $nama_1 . ' Indonesia';
                    $dataKK[$nama_2] = $nama_2 . ' 001';
                    $dataKK[$nama_3] = $nama_3 . ' 001';
                    $dataKK['kk_id'] = '0111111111000021';
                    break;
                default:
                    break;
            }
            // Gabungkan data KK dengan data PDD untuk setiap elemen
            $result[] = array_merge($dataKK, $pdd);
        }

        return $result;
    }
    public function columnFormats(): array
    {
        $formats = [];

        // Tentukan indeks kolom dan format tanggal berdasarkan tipe deskel
        $columns = [
            'Khusus' => ['B' => null, 'C' => null, 'G' => 'G', 'R' => null, 'S' => null, 'Z' => 'Z'],
            'Dasar' => ['C' => null, 'D' => null, 'H' => 'H', 'S' => null, 'T' => null, 'AA' => 'AA'],
            'Lengkap' => ['D' => null, 'F' => null, 'I' => 'I', 'T' => null, 'U' => null, 'AB' => 'AB'],
        ];

        // Ambil kolom berdasarkan tipe deskel
        $currentColumns = $columns[$this->deskel->deskel_tipe];

        // Atur format tanggal untuk kolom yang sesuai
        foreach ($currentColumns as $column => $dateFormat) {
            if ($dateFormat) {
                $formats[$column] = NumberFormat::FORMAT_DATE_DDMMYYYY;
            } else {
                $formats[$column] = NumberFormat::FORMAT_TEXT;
            }
        }

        return $formats;
    }


    public function headings(): array
    {
        $headings = [];

        $wilayahColumns = $this->getWilayahColumns();

        foreach ($wilayahColumns as $column) {
            $headings[] = $column;
        }

        $headings = array_merge($headings, [
            'NOMOR KK',
            'NIK',
            'NAMA LENGKAP',
            'JENIS KELAMIN',
            'TEMPAT LAHIR',
            'TANGGAL LAHIR',
            'AGAMA',
            'PENDIDIKAN',
            'PEKERJAAN',
            'KEWARGANEGARAAN',
            'NAMA AYAH',
            'NAMA IBU',
            'NIK AYAH',
            'NIK IBU',
            'ETNIS SUKU',
            'GOLONGAN DARAH',
            'STATUS PENDUDUK',
            'STATUS DASAR',
            'STATUS PERKAWINAN',
            'STATUS HUBUNGAN',
            'ALAMAT SEKARANG',
            'ALAMAT SEBELUMNYA',
            'TELEPON',
            'EMAIL',
            'TANGGAL UPDATE DATA',
        ]);

        return $headings;
    }

    public function collection()
    {
        return new Collection($this->getExampleData());
    }

    public function map($row): array
    {
        $tanggal_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($row['tanggal_lahir']);
        $tanggal_update_data = \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($row['tanggal_update_data']);

        $wilayahColumns = $this->getWilayahColumns();

        $result = [];

        foreach ($wilayahColumns as $column) {
            $result[$column] = $row[$column];
        }

        $result = array_merge($result, [
            'kk_id' => $row['kk_id'],
            'nik' => (string) $row['nik'],
            'nama_lengkap' => $row['nama_lengkap'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'tempat_lahir' => $row['tempat_lahir'],
            'tanggal_lahir' => $tanggal_lahir,
            'agama' => $row['agama'],
            'pendidikan' => $row['pendidikan'],
            'pekerjaan' => $row['pekerjaan'],
            'kewarganegaraan' => $row['kewarganegaraan'],
            'nama_ayah' =>  $row['nama_ayah'],
            'nama_ibu' =>  $row['nama_ibu'],
            'nik_ayah' =>  $row['nik_ayah'],
            'nik_ibu' =>  $row['nik_ibu'],
            'etnis_suku' => $row['etnis_suku'],
            'golongan_darah' => $row['golongan_darah'],
            'status_penduduk' => $row['status_penduduk'],
            'status_dasar' => $row['status_dasar'],
            'status_perkawinan' => $row['status_perkawinan'],
            'status_hubungan' => $row['status_hubungan'],
            'alamat_sekarang' => $row['alamat_sekarang'],
            'alamat_sebelumnya' => $row['alamat_sebelumnya'],
            'telepon' => $row['telepon'],
            'email' => $row['email'],
            'tanggal_update_data' => $tanggal_update_data,
        ]);

        return $result;
    }

    public function getWilayahColumns(): array
    {

        $columns = [];
        switch ($this->deskel->deskel_tipe) {
            case 'Khusus':
                $columns[] = $this->getFirstWordFromWilayah(0);
                break;
            case 'Dasar':
                for ($i = 0; $i <= 1; $i++) {
                    $columns[] = $this->getFirstWordFromWilayah($i);
                }
                break;
            case 'Lengkap':
                for ($i = 0; $i <= 2; $i++) {
                    $columns[] = $this->getFirstWordFromWilayah($i);
                }
                break;
            default:
                break;
        }
        return $columns;
    }
}