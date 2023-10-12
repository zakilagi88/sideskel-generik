<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum Pengajuan: string implements HasLabel, HasColor, HasIcon
{
    case DALAM_PROSES = 'DALAM PROSES';
    case SELESAI = 'SELESAI';
    case DIBATALKAN = 'DIBATALKAN';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::DALAM_PROSES => 'DALAM PROSES',
            self::SELESAI => 'SELESAI',
            self::DIBATALKAN => 'DIBATALKAN',
        };
    }

    public function getColor(): string | null | array
    {
        return match ($this) {
            self::DALAM_PROSES => 'info',
            self::SELESAI => 'success',
            self::DIBATALKAN => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DALAM_PROSES => 'fas-spinner',
            self::SELESAI => 'fas-check-double',
            self::DIBATALKAN => 'fas-xmark',
        };
    }
}
