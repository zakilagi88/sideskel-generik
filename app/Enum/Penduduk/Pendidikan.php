<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Pendidikan: string
{
        // ['SLTP/SEDERAJAT', 'SLTA/SEDERAJAT', 'DIPLOMA IV/ STRATA I', 'TIDAK/BLM SEKOLAH', 'BELUM TAMAT SD/SEDERAJAT', 'TAMAT SD/SEDERAJAT', 'AKADEMI/DIPLOMA III/SARJANA MUDA']
    case SLTP_SEDERAJAT = 'SLTP/SEDERAJAT';
    case SLTA_SEDERAJAT = 'SLTA/SEDERAJAT';
    case DIPLOMA_I_II = 'DIPLOMA I/II';
    case AKADEMI_DIPLOMA_III_SARJANA_MUDA = 'AKADEMI/DIPLOMA III/SARJANA MUDA';
    case DIPLOMA_IV_STRATA_I = 'DIPLOMA IV/STRATA I';
    case TIDAK_BELUM_SEKOLAH = 'TIDAK/BLM SEKOLAH';
    case BELUM_TAMAT_SD_SEDERAJAT = 'BELUM TAMAT SD/SEDERAJAT';
    case TAMAT_SD_SEDERAJAT = 'TAMAT SD/SEDERAJAT';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SLTP_SEDERAJAT => 'SLTP/SEDERAJAT',
            self::SLTA_SEDERAJAT => 'SLTA/SEDERAJAT',
            self::DIPLOMA_I_II => 'DIPLOMA I/II',
            self::AKADEMI_DIPLOMA_III_SARJANA_MUDA => 'AKADEMI/DIPLOMA III/SARJANA MUDA',
            self::DIPLOMA_IV_STRATA_I => 'DIPLOMA IV/STRATA I',
            self::TIDAK_BELUM_SEKOLAH => 'TIDAK/BLM SEKOLAH',
            self::BELUM_TAMAT_SD_SEDERAJAT => 'BELUM TAMAT SD/SEDERAJAT',
            self::TAMAT_SD_SEDERAJAT => 'TAMAT SD/SEDERAJAT',
        };
    }
}
