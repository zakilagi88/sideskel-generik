<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum KewarganegaraanType: string implements HasLabel
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
