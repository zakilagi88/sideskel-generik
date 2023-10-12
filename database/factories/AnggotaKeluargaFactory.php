<?php

namespace Database\Factories;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AnggotaKeluarga>
 */
class AnggotaKeluargaFactory extends Factory
{
    protected $model = AnggotaKeluarga::class;

    public function definition()
    {
        $hubungan = $this->faker->randomElement(['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain']);
        $kk_id = KartuKeluarga::inRandomOrder()->first()->kk_id;

        return [
            'nik' => Penduduk::inRandomOrder()->first()->nik,
            'kk_id' => $kk_id,
            'hubungan' => $hubungan,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (AnggotaKeluarga $anggota) {
            $this->state(function (array $attributes) use ($anggota) {
                return [
                    'nik' => Penduduk::whereDoesntHave('anggotaKeluarga', function ($query) use ($attributes) {
                        return $query->where('hubungan', $attributes['hubungan'])
                            ->where('kk_id', $attributes['kk_id']);
                    })->inRandomOrder()->first()->nik,
                ];
            });
        });
    }
}
