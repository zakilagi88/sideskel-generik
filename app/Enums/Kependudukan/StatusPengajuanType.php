<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;


enum StatusPengajuanType: string implements HasLabel, HasColor, HasIcon
{
    case BELUM_DIVERIFIKASI = 'BELUM DIVERIFIKASI';
    case DIVERIFIKASI = 'DIVERIFIKASI';
    case DIBATALKAN = 'DIBATALKAN';
    public function getLabel(): ?string
    {
        return match ($this) {
            self::BELUM_DIVERIFIKASI => 'BELUM DIVERIFIKASI',
            self::DIVERIFIKASI => 'DIVERIFIKASI',
            self::DIBATALKAN => 'DIBATALKAN',
        };
    }

    public function getColor(): string | null | array
    {
        return match ($this) {
            self::BELUM_DIVERIFIKASI => 'info',
            self::DIVERIFIKASI => 'success',
            self::DIBATALKAN => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::BELUM_DIVERIFIKASI => 'fas-spinner',
            self::DIVERIFIKASI => 'fas-check-double',
            self::DIBATALKAN => 'fas-xmark',
        };
    }
}
