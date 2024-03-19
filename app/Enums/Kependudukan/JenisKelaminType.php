<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum JenisKelaminType: string implements HasLabel
{
    case LAKI_LAKI = 'LAKI-LAKI';
    case PEREMPUAN = 'PEREMPUAN';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LAKI_LAKI => 'Laki-laki',
            self::PEREMPUAN => 'Perempuan',
        };
    }
}
