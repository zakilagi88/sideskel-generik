<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum PendidikanType: string implements HasLabel
{
    case TIDAK_BELUM_SEKOLAH = 'TIDAK/BELUM SEKOLAH';
    case BELUM_TAMAT_SD_SEDERAJAT = 'BELUM TAMAT SD/SEDERAJAT';
    case TAMAT_SD_SEDERAJAT = 'TAMAT SD/SEDERAJAT';
    case SLTP_SEDERAJAT = 'SLTP/SEDERAJAT';
    case SLTA_SEDERAJAT = 'SLTA/SEDERAJAT';
    case DIPLOMA_I_II = 'DIPLOMA I/II';
    case AKADEMI_DIPLOMA_III_SARJANA_MUDA = 'AKADEMI/DIPLOMA III/SARJANA MUDA';
    case DIPLOMA_IV_STRATA_I = 'DIPLOMA IV/STRATA I';
    case STRATA_II = 'STRATA II';
    case STRATA_III = 'STRATA III';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TIDAK_BELUM_SEKOLAH => 'Tidak/Belum Sekolah',
            self::BELUM_TAMAT_SD_SEDERAJAT => 'Belum Tamat SD/Sederajat',
            self::TAMAT_SD_SEDERAJAT => 'Tamat SD/Sederajat',
            self::SLTP_SEDERAJAT => 'SLTP/Sederajat',
            self::SLTA_SEDERAJAT => 'SLTA/Sederajat',
            self::DIPLOMA_I_II => 'Diploma I/II',
            self::AKADEMI_DIPLOMA_III_SARJANA_MUDA => 'Akademi/Diploma III/Sarjana Muda',
            self::DIPLOMA_IV_STRATA_I => 'Diploma IV/Strata I',
            self::STRATA_II => 'Strata II',
            self::STRATA_III => 'Strata III',
        };
    }
}
