<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data =
            [
                ['prov_id' => 63, 'prov_nama' => 'Kalimantan Selatan'],
            ];

        foreach ($data as $item) {
            Provinsi::create($item);
        }
    }
}
