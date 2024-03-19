<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum GolonganDarahType: string implements HasLabel
{
    case A = 'A';
    case B = 'B';
    case AB = 'AB';
    case O = 'O';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::A => 'A',
            self::B => 'B',
            self::AB => 'AB',
            self::O => 'O',
        };
    }
}
