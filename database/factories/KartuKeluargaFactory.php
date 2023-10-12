<?php

namespace Database\Factories;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KartuKeluarga>
 */

class KartuKeluargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = KartuKeluarga::class;

    public function definition(): array
    {
        return [
            'kk_id' => 'KK' . $this->faker->numerify('########'),
            'kk_kepala' => null,
            'kk_alamat' => $this->faker->address(),
            'sls_id' => SLS::inRandomOrder()->first()->sls_id,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}
