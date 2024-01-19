<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Agama: string implements HasLabel
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
            self::ISLAM => 'ISLAM',
            self::KRISTEN => 'KRISTEN',
            self::KATHOLIK => 'KATHOLIK',
            self::HINDU => 'HINDU',
            self::BUDDHA => 'BUDDHA',
            self::KONGHUCU => 'KONGHUCU',
        };
    }
}