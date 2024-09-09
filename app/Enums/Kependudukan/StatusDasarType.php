<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum StatusDasarType: string implements HasLabel, HasColor, HasIcon
{
    case HIDUP = 'HIDUP';
    case PINDAH = 'PINDAH';
    case BELUM_VALID = 'BELUM VALID';
    case MENINGGAL = 'MENINGGAL';
    case HILANG = 'HILANG';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::HIDUP => 'Hidup',
            self::PINDAH => 'Pindah',
            self::BELUM_VALID => 'Belum Valid',
            self::MENINGGAL => 'Meninggal',
            self::HILANG => 'Hilang',
        };
    }

    public function getColor(): string | null | array
    {
        return match ($this) {
            self::HIDUP => 'success',
            self::PINDAH => 'primary',
            self::BELUM_VALID => 'info',
            self::MENINGGAL => 'danger',
            self::HILANG => 'warning',
        };
    }

    public function   getIcon(): ?string
    {
        return match ($this) {
            self::HIDUP => 'fas-children',
            self::PINDAH => 'fas-person-walking-dashed-line-arrow-right',
            self::BELUM_VALID => 'fas-arrows-down-to-people',
            self::MENINGGAL => 'fas-person-falling-burst',
            self::HILANG => 'fas-exclamation-triangle',
        };
    }
}
