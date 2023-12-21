<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum Status: string implements HasLabel, HasColor, HasIcon
{
    case WARGA = 'WARGA';
    case PINDAH = 'PINDAH';
    case PENDATANG = 'PENDATANG';
    case MENINGGAL = 'MENINGGAL';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::WARGA => 'WARGA',
            self::PINDAH => 'PINDAH',
            self::PENDATANG => 'PENDATANG',
            self::MENINGGAL => 'MENINGGAL',
        };
    }

    public function getColor(): string | null | array
    {
        return match ($this) {
            self::WARGA => 'success',
            self::PINDAH => 'primary',
            self::PENDATANG => 'info',
            self::MENINGGAL => 'danger',
        };
    }

    public function   getIcon(): ?string
    {
        return match ($this) {
            self::WARGA => 'fas-children',
            self::PINDAH => 'fas-person-walking-dashed-line-arrow-right',
            self::PENDATANG => 'fas-arrows-down-to-people',
            self::MENINGGAL => 'fas-person-falling-burst',
        };
    }
}
