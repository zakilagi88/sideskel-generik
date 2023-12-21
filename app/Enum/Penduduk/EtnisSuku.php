<?php

namespace App\Enum\Penduduk;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum EtnisSuku: string
{
    case BATAK = 'BATAK';
    case MELAYU = 'MELAYU';
    case SUNDA = 'SUNDA';
    case JAWA = 'JAWA';
    case MADURA = 'MADURA';
    case BANJAR = 'BANJAR';
    case DAYAK = 'DAYAK';
    case BUGIS = 'BUGIS';
    case MINANGKABAU = 'MINANGKABAU';
    case CINA = 'CINA';
    case BIMA = 'BIMA';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::BATAK => 'BATAK',
            self::MELAYU => 'MELAYU',
            self::SUNDA => 'SUNDA',
            self::JAWA => 'JAWA',
            self::MADURA => 'MADURA',
            self::BANJAR => 'BANJAR',
            self::DAYAK => 'DAYAK',
            self::BUGIS => 'BUGIS',
            self::MINANGKABAU => 'MINANGKABAU',
            self::CINA => 'CINA',
            self::BIMA => 'BIMA',
        };
    }
}
