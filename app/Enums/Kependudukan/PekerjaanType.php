<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum PekerjaanType: string implements HasLabel
{

    case AKUNTAN = 'AKUNTAN';
    case ANGGOTA_BPK = 'ANGGOTA BPK';
    case ANGGOTA_DPD = 'ANGGOTA DPD';
    case ANGGOTA_DPRD_KABUPATEN_KOTA = 'ANGGOTA DPRD KABUPATEN/KOTA';
    case ANGGOTA_DPRD_PROVINSI = 'ANGGOTA DPRD PROVINSI';
    case ANGGOTA_DPR_RI = 'ANGGOTA DPR RI';
    case ANGGOTA_KABINET_KEMENTRIAN = 'ANGGOTA KABINET/KEMENTRIAN';
    case ANGGOTA_MAHKAMAH_KONSTITUSI = 'ANGGOTA MAHKAMAH KONSTITUSI';
    case APOTEKER = 'APOTEKER';
    case ARSITEK = 'ARSITEK';
    case BELUM_TIDAK_BEKERJA = 'BELUM/TIDAK BEKERJA';
    case BIARAWATI = 'BIARAWATI';
    case BIDAN = 'BIDAN';
    case BUPATI = 'BUPATI';
    case BURUH_HARIAN_LEPAS = 'BURUH HARIAN LEPAS';
    case BURUH_NELAYAN_PERIKANAN = 'BURUH NELAYAN/PERIKANAN';
    case BURUH_PETERNAKAN = 'BURUH PETERNAKAN';
    case BURUH_TANI_PERKEBUNAN = 'BURUH TANI/PERKEBUNAN';
    case DOKTER = 'DOKTER';
    case DOSEN = 'DOSEN';
    case DUTA_BESAR = 'DUTA BESAR';
    case GUBERNUR = 'GUBERNUR';
    case GURU = 'GURU';
    case IMAM_MASJID = 'IMAM MASJID';
    case INDUSTRI = 'INDUSTRI';
    case JURU_MASAK = 'JURU MASAK';
    case KARYAWAN_BUMD = 'KARYAWAN BUMD';
    case KARYAWAN_BUMN = 'KARYAWAN BUMN';
    case KARYAWAN_HONORER = 'KARYAWAN HONORER';
    case KARYAWAN_SWASTA = 'KARYAWAN SWASTA';
    case KEPALA_DESA = 'KEPALA DESA';
    case KEPOLISIAN_RI = 'KEPOLISIAN RI';
    case KONSTRUKSI = 'KONSTRUKSI';
    case KONSULTAN = 'KONSULTAN';
    case LAINNYA = 'LAINNYA';
    case MEKANIK = 'MEKANIK';
    case MENGURUS_RUMAH_TANGGA = 'MENGURUS RUMAH TANGGA';
    case NELAYAN_PERIKANAN = 'NELAYAN/PERIKANAN';
    case NOTARIS = 'NOTARIS';
    case PARAJI = 'PARAJI';
    case PARANORMAL = 'PARANORMAL';
    case PASTOR = 'PASTOR';
    case PEDAGANG = 'PEDAGANG';
    case PEGAWAI_NEGERI_SIPIL = 'PEGAWAI NEGERI SIPIL';
    case PELAJAR_MAHASISWA = 'PELAJAR/MAHASISWA';
    case PELAUT = 'PELAUT';
    case PEMBANTU_RUMAH_TANGGA = 'PEMBANTU RUMAH TANGGA';
    case PENATA_BUSANA = 'PENATA BUSANA';
    case PENATA_RAMBUT = 'PENATA RAMBUT';
    case PENATA_RIAS = 'PENATA RIAS';
    case PENDETA = 'PENDETA';
    case PENELITI = 'PENELITI';
    case PENGACARA = 'PENGACARA';
    case PENSIUNAN = 'PENSIUNAN';
    case PENTERJEMAH = 'PENTERJEMAH';
    case PENYIAR_RADIO = 'PENYIAR RADIO';
    case PENYIAR_TELEVISI = 'PENYIAR TELEVISI';
    case PERANCANG_BUSANA = 'PERANCANG BUSANA';
    case PERANGKAT_DESA = 'PERANGKAT DESA';
    case PERAWAT = 'PERAWAT';
    case PERDAGANGAN = 'PERDAGANGAN';
    case PETANI_PEKEBUN = 'PETANI/PEKEBUN';
    case PETERNAK = 'PETERNAK';
    case PIALANG = 'PIALANG';
    case PILOT = 'PILOT';
    case PRESIDEN = 'PRESIDEN';
    case PROMOTOR_ACARA = 'PROMOTOR ACARA';
    case PSIKIATER_PSIKOLOG = 'PSIKIATER/PSIKOLOG';
    case SENIMAN = 'SENIMAN';
    case SOPIR = 'SOPIR';
    case TABIB = 'TABIB';
    case TENTARA_NASIONAL_INDONESIA = 'TENTARA NASIONAL INDONESIA';
    case TRANSPORTASI = 'TRANSPORTASI';
    case TUKANG_BATU = 'TUKANG BATU';
    case TUKANG_CUKUR = 'TUKANG CUKUR';
    case TUKANG_GIGI = 'TUKANG GIGI';
    case TUKANG_JAHIT = 'TUKANG JAHIT';
    case TUKANG_KAYU = 'TUKANG KAYU';
    case TUKANG_LAS_PANDAI_BESI = 'TUKANG LAS/PANDAI BESI';
    case TUKANG_LISTRIK = 'TUKANG LISTRIK';
    case TUKANG_SOL_SEPATU = 'TUKANG SOL SEPATU';
    case USTADZ_MUBALIGH = 'USTADZ/MUBALIGH';
    case WAKIL_BUPATI = 'WAKIL BUPATI';
    case WAKIL_GUBERNUR = 'WAKIL GUBERNUR';
    case WAKIL_PRESIDEN = 'WAKIL PRESIDEN';
    case WAKIL_WALIKOTA = 'WAKIL WALIKOTA';
    case WALIKOTA = 'WALIKOTA';
    case WARTAWAN = 'WARTAWAN';
    case WIRASWASTA = 'WIRASWASTA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BELUM_TIDAK_BEKERJA => 'BELUM/TIDAK BEKERJA',
            self::MENGURUS_RUMAH_TANGGA => 'MENGURUS RUMAH TANGGA',
            self::PELAJAR_MAHASISWA => 'PELAJAR/MAHASISWA',
            self::PENSIUNAN => 'PENSIUNAN',
            self::PEGAWAI_NEGERI_SIPIL => 'PEGAWAI NEGERI SIPIL',
            self::TENTARA_NASIONAL_INDONESIA => 'TENTARA NASIONAL INDONESIA',
            self::KEPOLISIAN_RI => 'KEPOLISIAN RI',
            self::PERDAGANGAN => 'PERDAGANGAN',
            self::PETANI_PEKEBUN => 'PETANI/PEKEBUN',
            self::PETERNAK => 'PETERNAK',
            self::NELAYAN_PERIKANAN => 'NELAYAN/PERIKANAN',
            self::INDUSTRI => 'INDUSTRI',
            self::KONSTRUKSI => 'KONSTRUKSI',
            self::TRANSPORTASI => 'TRANSPORTASI',
            self::KARYAWAN_SWASTA => 'KARYAWAN SWASTA',
            self::KARYAWAN_BUMN => 'KARYAWAN BUMN',
            self::KARYAWAN_BUMD => 'KARYAWAN BUMD',
            self::KARYAWAN_HONORER => 'KARYAWAN HONORER',
            self::BURUH_HARIAN_LEPAS => 'BURUH HARIAN LEPAS',
            self::BURUH_TANI_PERKEBUNAN => 'BURUH TANI/PERKEBUNAN',
            self::BURUH_NELAYAN_PERIKANAN => 'BURUH NELAYAN/PERIKANAN',
            self::BURUH_PETERNAKAN => 'BURUH PETERNAKAN',
            self::PEMBANTU_RUMAH_TANGGA => 'PEMBANTU RUMAH TANGGA',
            self::TUKANG_CUKUR => 'TUKANG CUKUR',
            self::TUKANG_LISTRIK => 'TUKANG LISTRIK',
            self::TUKANG_BATU => 'TUKANG BATU',
            self::TUKANG_KAYU => 'TUKANG KAYU',
            self::TUKANG_SOL_SEPATU => 'TUKANG SOL SEPATU',
            self::TUKANG_LAS_PANDAI_BESI => 'TUKANG LAS/PANDAI BESI',
            self::TUKANG_JAHIT => 'TUKANG JAHIT',
            self::TUKANG_GIGI => 'TUKANG GIGI',
            self::PENATA_RIAS => 'PENATA RIAS',
            self::PENATA_BUSANA => 'PENATA BUSANA',
            self::PENATA_RAMBUT => 'PENATA RAMBUT',
            self::MEKANIK => 'MEKANIK',
            self::SENIMAN => 'SENIMAN',
            self::TABIB => 'TABIB',
            self::PARAJI => 'PARAJI',
            self::PERANCANG_BUSANA => 'PERANCANG BUSANA',
            self::PENTERJEMAH => 'PENTERJEMAH',
            self::IMAM_MASJID => 'IMAM MASJID',
            self::PENDETA => 'PENDETA',
            self::PASTOR => 'PASTOR',
            self::WARTAWAN => 'WARTAWAN',
            self::USTADZ_MUBALIGH => 'USTADZ/MUBALIGH',
            self::JURU_MASAK => 'JURU MASAK',
            self::PROMOTOR_ACARA => 'PROMOTOR ACARA',
            self::ANGGOTA_DPR_RI => 'ANGGOTA DPR RI',
            self::ANGGOTA_DPD => 'ANGGOTA DPD',
            self::ANGGOTA_BPK => 'ANGGOTA BPK',
            self::PRESIDEN => 'PRESIDEN',
            self::WAKIL_PRESIDEN => 'WAKIL PRESIDEN',
            self::ANGGOTA_MAHKAMAH_KONSTITUSI => 'ANGGOTA MAHKAMAH KONSTITUSI',
            self::ANGGOTA_KABINET_KEMENTRIAN => 'ANGGOTA KABINET/KEMENTRIAN',
            self::DUTA_BESAR => 'DUTA BESAR',
            self::GUBERNUR => 'GUBERNUR',
            self::WAKIL_GUBERNUR => 'WAKIL GUBERNUR',
            self::BUPATI => 'BUPATI',
            self::WAKIL_BUPATI => 'WAKIL BUPATI',
            self::WALIKOTA => 'WALIKOTA',
            self::WAKIL_WALIKOTA => 'WAKIL WALIKOTA',
            self::ANGGOTA_DPRD_PROVINSI => 'ANGGOTA DPRD PROVINSI',
            self::ANGGOTA_DPRD_KABUPATEN_KOTA => 'ANGGOTA DPRD KABUPATEN/KOTA',
            self::DOSEN => 'DOSEN',
            self::GURU => 'GURU',
            self::PILOT => 'PILOT',
            self::PENGACARA => 'PENGACARA',
            self::NOTARIS => 'NOTARIS',
            self::ARSITEK => 'ARSITEK',
            self::AKUNTAN => 'AKUNTAN',
            self::KONSULTAN => 'KONSULTAN',
            self::DOKTER => 'DOKTER',
            self::BIDAN => 'BIDAN',
            self::PERAWAT => 'PERAWAT',
            self::APOTEKER => 'APOTEKER',
            self::PSIKIATER_PSIKOLOG => 'PSIKIATER/PSIKOLOG',
            self::PENYIAR_TELEVISI => 'PENYIAR TELEVISI',
            self::PENYIAR_RADIO => 'PENYIAR RADIO',
            self::PELAUT => 'PELAUT',
            self::PENELITI => 'PENELITI',
            self::SOPIR => 'SOPIR',
            self::PIALANG => 'PIALANG',
            self::PARANORMAL => 'PARANORMAL',
            self::PEDAGANG => 'PEDAGANG',
            self::PERANGKAT_DESA => 'PERANGKAT DESA',
            self::KEPALA_DESA => 'KEPALA DESA',
            self::BIARAWATI => 'BIARAWATI',
            self::WIRASWASTA => 'WIRASWASTA',
            self::LAINNYA => 'LAINNYA',
        };
    }
}