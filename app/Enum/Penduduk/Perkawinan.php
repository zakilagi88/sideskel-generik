<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum Perkawinan: string
{
    case KAWIN = 'KAWIN';
    case KAWIN_TERCATAT = 'KAWIN TERCATAT';
    case KAWIN_BELUM_TERCATAT = 'KAWIN BELUM TERCATAT';
    case BELUM_KAWIN = 'BELUM KAWIN';
    case CERAI_BELUM_TERCATAT = 'CERAI BELUM TERCATAT';
    case CERAI_HIDUP = 'CERAI HIDUP';
    case CERAI_HIDUP_BELUM_TERCATAT = 'CERAI HIDUP BELUM TERCATAT';
    case CERAI_HIDUP_TERCATAT = 'CERAI HIDUP TERCATAT';
    case CERAI_MATI = 'CERAI MATI';
    case CERAI_TERCATAT = 'CERAI TERCATAT';



    public function getLabel(): ?string
    {
        return match ($this) {
            self::KAWIN => 'KAWIN',
            self::KAWIN_TERCATAT => 'KAWIN TERCATAT',
            self::KAWIN_BELUM_TERCATAT => 'KAWIN BELUM TERCATAT',
            self::BELUM_KAWIN => 'BELUM KAWIN',
            self::CERAI_BELUM_TERCATAT => 'CERAI BELUM TERCATAT',
            self::CERAI_HIDUP => 'CERAI HIDUP',
            self::CERAI_HIDUP_BELUM_TERCATAT => 'CERAI HIDUP BELUM TERCATAT',
            self::CERAI_HIDUP_TERCATAT => 'CERAI HIDUP TERCATAT',
            self::CERAI_MATI => 'CERAI MATI',
            self::CERAI_TERCATAT => 'CERAI TERCATAT',
        };
    }
}
