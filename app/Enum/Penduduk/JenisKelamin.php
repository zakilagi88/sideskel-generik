<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum JenisKelamin: string implements HasLabel
{
    case LAKI_LAKI = 'LAKI-LAKI';
    case PEREMPUAN = 'PEREMPUAN';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LAKI_LAKI => 'LAKI-LAKI',
            self::PEREMPUAN => 'PEREMPUAN',
        };
    }
}
