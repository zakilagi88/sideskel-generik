<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KartuKeluargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KartuKeluarga::factory(4)->create();
    }
}
