<?php

namespace Database\Factories;

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;
use Illuminate\Database\Eloquent\Factories\Factory;

class PendudukFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Penduduk::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            // 'kk_id' => KartuKeluarga::inRandomOrder()->first()->kk_id,
            'nik' => $this->faker->numerify('###############'),
            'nama_lengkap' => $this->faker->name(),
            'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date(),
            'agama' => $this->faker->randomElement(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu']),
            'pendidikan' => $this->faker->randomElement(['Tidak Sekolah', 'SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'D4', 'S1', 'S2', 'S3']),
            'golongan_darah' => $this->faker->randomElement(['A', 'B', 'AB', 'O']),
            'status_pernikahan' => $this->faker->randomElement(['Kawin', 'Belum Kawin', 'Cerai Hidup', 'Cerai Mati']),
            'pekerjaan' => $this->faker->jobTitle(),
            'kewarganegaraan' => $this->faker->randomElement(['WNI', 'WNA']),
            'status' => $this->faker->randomElement(['Warga', 'Mati', 'Pindah']),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
}
