<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Kewarganegaraan: string implements HasLabel
{
    case WNI = 'WNI';
    case WNA = 'WNA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::WNI => 'WNI',
            self::WNA => 'WNA',
        };
    }
}