<?php

namespace App\Enums;

use App\Enums\Kependudukan\{AgamaType, PendidikanType, PekerjaanType, PerkawinanType, KewarganegaraanType, StatusHubunganType, GolonganDarahType, JenisKelaminType, KategoriUmurType};
use Filament\Support\Contracts\HasLabel;

enum PendudukType: string implements HasLabel
{
    case agama = AgamaType::class;
    case pendidikan = PendidikanType::class;
    case pekerjaan = PekerjaanType::class;
    case status_perkawinan = PerkawinanType::class;
    case kewarganegaraan = KewarganegaraanType::class;
    case status_hubungan = StatusHubunganType::class;
    case gologan_darah = GolonganDarahType::class;
    case jenis_kelamin = JenisKelaminType::class;
    case kategori_umur = KategoriUmurType::class;


    public function getLabel(): ?string
    {
        return match ($this) {
            self::agama => 'Agama',
            self::pendidikan => 'Pendidikan',
            self::pekerjaan => 'Pekerjaan',
            self::status_perkawinan => 'Perkawinan',
            self::kewarganegaraan => 'Kewarganegaraan',
            self::status_hubungan => 'Status Hubungan',
            self::gologan_darah => 'Golongan Darah',
            self::jenis_kelamin => 'Jenis Kelamin',
            self::kategori_umur => 'Kategori Umur',
        };
    }
}
