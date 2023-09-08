<?php

namespace Database\Factories;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
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
            'kk_no' => $this->faker->unique()->randomNumber(9),
            'kk_alamat' => $this->faker->address(),
            'rt_id' => function () {
                return RT::inRandomOrder()->first()->rt_id;
            },
            'rw_id' => function () {
                return RW::inRandomOrder()->first()->rw_id;
            },
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */

    public function configure()
    {
        return $this->afterCreating(function (KartuKeluarga $kartuKeluarga) {
            Penduduk::factory()
                ->count(2)
                ->for($kartuKeluarga, 'kartuKeluarga')
                ->create();
        });
    }
}
