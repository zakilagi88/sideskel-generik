<?php

namespace App\Services;

use App\Enums\PendudukType;
use Illuminate\Support\Collection;

class GenerateEnumUnionQuery
{
    public static function getSubQuery(string $enumClass, string $key): string
    {
        $enumCases = $enumClass::cases();

        $enumValues = collect($enumCases)->map(function ($enum) use ($key) {
            return "SELECT '{$enum->value}' AS {$key}, 0 AS laki_laki, 0 AS perempuan";
        });

        return $enumValues->implode(' UNION ALL ');
    }

    public static function getEnumClassByKeyName(string $key): string
    {
        $enumCases = PendudukType::cases();

        $key = collect($enumCases)->filter(function ($enum) use ($key) {
            return $enum->name === $key;
        })->first();

        return $key->value;
    }
}
