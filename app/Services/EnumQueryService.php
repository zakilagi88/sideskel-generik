<?php

namespace App\Services;

use App\Enums\Kependudukan\RentangUmurType;
use App\Enums\PendudukType;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class EnumQueryService
{
    public static function getSubQuery(string $enumClass, string $key): string
    {
        $enumCases = $enumClass::cases();

        $enumValues = collect($enumCases)->map(function ($enum) use ($key) {
            return "SELECT '{$enum->value}' AS {$key}, 0 AS laki_laki, 0 AS perempuan";
        });

        return $enumValues->implode(' UNION ALL ');
    }

    public static function getUmurSubQuery(Collection $enumCases, string $key): string
    {
        $query = $enumCases->map(function ($enum) {
            $umur = $enum->value;

            if ($umur === RentangUmurType::UMUR_75_PLUS->value) {
                return "WHEN penduduk.umur >= 75 THEN '75+'";
            } else {
                list($umurAwal, $umurAkhir) = explode('-', $umur);
                return "WHEN penduduk.umur BETWEEN {$umurAwal} AND {$umurAkhir} THEN 'Umur {$umur}'";
            }
        })->implode(' ');

        return "CASE {$query} END AS {$key}";
    }

    public static function getEnumClassByKeyName(string $key): string
    {
        $enumCases = static::getEnumClasses()->get($key);

        return $enumCases ?: throw new \InvalidArgumentException("Enumerator dengan kunci yang cocok tidak ditemukan.");
    }

    public static function getEnumPath()
    {
        return app(Filesystem::class)->files(app_path('Enums/Kependudukan'));
    }

    public static function getEnumClasses(): Collection
    {
        $enumFiles = static::getEnumPath();

        return collect($enumFiles)->flatMap(function ($file) {
            $fileName = $file->getFilenameWithoutExtension();

            $name = str_replace('Type', '', $fileName);
            $name = preg_replace('/(?<!^)([A-Z])/', '_$1', $name);
            $name = strtolower($name);

            return [
                $name => "App\Enums\Kependudukan\\{$fileName}",
            ];
        });
    }

    public static function getEnumOptions(): Collection
    {
        $enumFiles = static::getEnumPath();

        return collect($enumFiles)->flatMap(function ($file) {
            $fileName = $file->getFilenameWithoutExtension();

            $fileLabel = str_replace('Type', '', $fileName);
            $fileLabel = ucwords(preg_replace('/(?<!^)([A-Z])/', ' $1', $fileLabel));

            $name = str_replace('Type', '', $fileName);
            $name = strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $name));

            return [
                $name => $fileLabel,
            ];
        });
    }
}
