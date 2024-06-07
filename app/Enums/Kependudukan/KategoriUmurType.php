<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;

enum KategoriUmurType: string implements HasLabel
{
    case BADUTA = 'BADUTA';
    case BALITA = 'BALITA';
    case ANAK_ANAK = 'ANAK-ANAK';
    case REMAJA = 'REMAJA';
    case DEWASA = 'DEWASA';
    case LANSIA = 'LANSIA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BADUTA => 'BADUTA',
            self::BALITA => 'BALITA',
            self::ANAK_ANAK => 'Anak-Anak',
            self::REMAJA => 'Remaja',
            self::DEWASA => 'Dewasa',
            self::LANSIA => 'Lansia',
        };
    }
}