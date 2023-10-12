<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Hubungan: string implements HasLabel
{
    case KEPALA_KELUARGA = 'KEPALA KELUARGA';
    case SUAMI = 'SUAMI';
    case ISTRI = 'ISTRI';
    case ANAK = 'ANAK';
    case MENANTU = 'MENANTU';
    case CUCU = 'CUCU';
    case ORANG_TUA = 'ORANG TUA';
    case MERTUA = 'MERTUA';
    case FAMILI_LAIN = 'FAMILI LAIN';
    case PEMBANTU = 'PEMBANTU';
    case LAINNYA = 'LAINNYA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::KEPALA_KELUARGA => 'KEPALA KELUARGA',
            self::SUAMI => 'SUAMI',
            self::ISTRI => 'ISTRI',
            self::ANAK => 'ANAK',
            self::MENANTU => 'MENANTU',
            self::CUCU => 'CUCU',
            self::ORANG_TUA => 'ORANG TUA',
            self::MERTUA => 'MERTUA',
            self::FAMILI_LAIN => 'FAMILI LAIN',
            self::PEMBANTU => 'PEMBANTU',
            self::LAINNYA => 'LAINNYA',
        };
    }
}
