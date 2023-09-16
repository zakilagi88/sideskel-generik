<?php

namespace Database\Factories;

use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SLS>
 */
class SLSFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Ambil rw_id dan rt_id secara acak
        $rw = RW::inRandomOrder()->first();
        $rt = RT::inRandomOrder()->first();

        $rw_id = $rw->rw_id;
        $rt_id = $rt->rt_id;

        return [
            'rw_id' => $rw_id,
            'rt_id' => $rt_id,
            'sls_nama' => $this->faker->name(),
            // Metode generateUniqueSlsKode dihapus dari sini
        ];
    }
}