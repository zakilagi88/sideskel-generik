<?php

namespace App\Enums\Desa;

use Filament\Support\Contracts\HasLabel;


enum TipologiType: string implements HasLabel
{
    case PERSAWAHAN = 'PERSAWAHAN';
    case PERLADANGAN = 'PERLADANGAN';
    case PERKEBUNAN = 'PERKEBUNAN';
    case PETERNAKAN = 'PETERNAKAN';
    case PERTANIAN = 'PERTANIAN';
    case NELAYAN = 'NELAYAN';
    case PERTAMBANGAN_GALIAN = 'PERTAMBANGAN/GALIAN';
    case KERAJINAN_INDUSTRI_KECIL = 'KERAJINAN/INDUSTRI KECIL';
    case INDUSTRI_SEDANG_DAN_BESAR = 'INDUSTRI SEDANG DAN BESAR';
    case JASA_DAN_PERDAGANGAN = 'JASA DAN PERDAGANGAN';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::PERSAWAHAN => 'PERSAWAHAN',
            self::PERLADANGAN => 'PERLADANGAN',
            self::PERKEBUNAN => 'PERKEBUNAN',
            self::PETERNAKAN => 'PETERNAKAN',
            self::PERTANIAN => 'PERTANIAN',
            self::NELAYAN => 'NELAYAN',
            self::PERTAMBANGAN_GALIAN => 'PERTAMBANGAN/GALIAN',
            self::KERAJINAN_INDUSTRI_KECIL => 'KERAJINAN/INDUSTRI KECIL',
            self::INDUSTRI_SEDANG_DAN_BESAR => 'INDUSTRI SEDANG DAN BESAR',
            self::JASA_DAN_PERDAGANGAN => 'JASA DAN PERDAGANGAN',
        };
    }
}
