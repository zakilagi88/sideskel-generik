<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;

enum JenisKelaminType: string implements HasLabel
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
