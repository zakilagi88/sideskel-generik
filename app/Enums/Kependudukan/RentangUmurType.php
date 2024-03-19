<?php

namespace App\Enums\Kependudukan;

use Filament\Support\Contracts\HasLabel;

enum RentangUmurType: string implements HasLabel
{
    case UMUR_0_4 = '0-4';
    case UMUR_5_9 = '5-9';
    case UMUR_10_14 = '10-14';
    case UMUR_15_19 = '15-19';
    case UMUR_20_24 = '20-24';
    case UMUR_25_29 = '25-29';
    case UMUR_30_34 = '30-34';
    case UMUR_35_39 = '35-39';
    case UMUR_40_44 = '40-44';
    case UMUR_45_49 = '45-49';
    case UMUR_50_54 = '50-54';
    case UMUR_55_59 = '55-59';
    case UMUR_60_64 = '60-64';
    case UMUR_65_69 = '65-69';
    case UMUR_70_74 = '70-74';
    case UMUR_75_PLUS = '75+';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::UMUR_0_4 => '0-4',
            self::UMUR_5_9 => '5-9',
            self::UMUR_10_14 => '10-14',
            self::UMUR_15_19 => '15-19',
            self::UMUR_20_24 => '20-24',
            self::UMUR_25_29 => '25-29',
            self::UMUR_30_34 => '30-34',
            self::UMUR_35_39 => '35-39',
            self::UMUR_40_44 => '40-44',
            self::UMUR_45_49 => '45-49',
            self::UMUR_50_54 => '50-54',
            self::UMUR_55_59 => '55-59',
            self::UMUR_60_64 => '60-64',
            self::UMUR_65_69 => '65-69',
            self::UMUR_70_74 => '70-74',
            self::UMUR_75_PLUS => '75+',
        };
    }
}
