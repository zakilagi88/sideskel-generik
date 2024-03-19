<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;

enum StatusTempatTinggalType: string implements HasLabel
{
    case MILIK_SENDIRI = 'MILIK SENDIRI';
    case KONTRAK = 'KONTRAK';
    case SEWA = 'SEWA';
    case BEBAS_SEWA_MILIK_ORANG_LAIN = 'BEBAS SEWA MILIK ORANG LAIN';
    case RUMAH_MILIK_ORANG_TUA_SANAK_SAUDARA = 'RUMAH MILIK ORANG TUA/SANAK/SAUDARA';
    case RUMAH_DINAS = 'RUMAH DINAS';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::MILIK_SENDIRI => 'MILIK SENDIRI',
            self::KONTRAK => 'KONTRAK',
            self::SEWA => 'SEWA',
            self::BEBAS_SEWA_MILIK_ORANG_LAIN => 'BEBAS SEWA MILIK ORANG LAIN',
            self::RUMAH_MILIK_ORANG_TUA_SANAK_SAUDARA => 'RUMAH MILIK ORANG TUA/SANAK/SAUDARA',
            self::RUMAH_DINAS => 'RUMAH DINAS',
        };
    }
}
