<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum StatusHubunganType: string implements HasLabel
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
            self::KEPALA_KELUARGA => 'Kepala Keluarga',
            self::SUAMI => 'Suami',
            self::ISTRI => 'Istri',
            self::ANAK => 'Anak',
            self::MENANTU => 'Menantu',
            self::CUCU => 'Cucu',
            self::ORANG_TUA => 'Orang Tua',
            self::MERTUA => 'Mertua',
            self::FAMILI_LAIN => 'Famili Lain',
            self::PEMBANTU => 'Pembantu',
            self::LAINNYA => 'Lainnya',
        };
    }
}
