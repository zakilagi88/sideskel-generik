<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum Status: string implements HasLabel, HasColor, HasIcon
{
    case YA = 'YA';
    case TIDAK = 'TIDAK';
    case PENDATANG = 'PENDATANG';
    case MENINGGAL = 'MENINGGAL';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::YA => 'WARGA',
            self::TIDAK => 'PINDAH',
            self::PENDATANG => 'PENDATANG',
            self::MENINGGAL => 'MENINGGAL',
        };
    }

    public function getColor(): string | null | array
    {
        return match ($this) {
            self::YA => 'success',
            self::TIDAK => 'primary',
            self::PENDATANG => 'info',
            self::MENINGGAL => 'danger',
        };
    }

    public function   getIcon(): ?string
    {
        return match ($this) {
            self::YA => 'fas-children',
            self::TIDAK => 'fas-person-walking-dashed-line-arrow-right',
            self::PENDATANG => 'fas-arrows-down-to-people',
            self::MENINGGAL => 'fas-person-falling-burst',
        };
    }
}
