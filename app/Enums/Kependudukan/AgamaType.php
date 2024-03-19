<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;

enum AgamaType: string implements HasLabel
{
    case ISLAM = 'ISLAM';
    case KRISTEN = 'KRISTEN';
    case KATHOLIK = 'KATHOLIK';
    case HINDU = 'HINDU';
    case BUDDHA = 'BUDDHA';
    case KONGHUCU = 'KONGHUCU';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ISLAM => 'Islam',
            self::KRISTEN => 'Kristen',
            self::KATHOLIK => 'Katolik',
            self::HINDU => 'Hindu',
            self::BUDDHA => 'Buddha',
            self::KONGHUCU => 'Konghucu',
        };
    }
}
