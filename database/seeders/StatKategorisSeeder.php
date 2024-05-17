<?php

namespace Database\Seeders;

use App\Models\StatKategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatKategorisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['stat_kategoris'] as $key => $value) {
            StatKategori::updateOrCreate(
                ['id' => $key],
                [
                    'nama' => $value['nama'],
                ]
            );
        }
    }
}
