<?php

namespace Database\Seeders;

use App\Models\StatSDM;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatSDMSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['stats'] as $key => $value) {
            StatSDM::updateOrCreate(
                ['id' => $key],
                [
                    'stat_kategori_id' => $value['stat_kategori_id'],
                    'key' => $value['key'],
                    'nama' => $value['nama'],
                    'slug' => $value['slug'],
                    'deskripsi' => $value['deskripsi'],
                    'status' => $value['status'],
                ]
            );
        }
    }
}