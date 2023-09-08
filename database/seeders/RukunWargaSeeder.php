<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RW;

class RukunWargaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['rw_nama' => 'RW 001', 'kelurahan_id' => 1],
            ['rw_nama' => 'RW 002', 'kelurahan_id' => 2],
            // Tambahkan data lain sesuai kebutuhan
        ];

        foreach ($data as $item) {
            RW::create($item);
        }
    }
}
