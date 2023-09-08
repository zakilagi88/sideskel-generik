<?php

namespace Database\Seeders;

use App\Models\RT;
use Illuminate\Database\Seeder;
use App\Models\RukunTetangga; // Ganti dengan namespace model Anda

class RukunTetanggaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['rt_nama' => 'RT 001', 'rw_id' => 1],
            ['rt_nama' => 'RT 002', 'rw_id' => 2],
            // Tambahkan data lain sesuai kebutuhan
        ];

        foreach ($data as $item) {
            RT::create($item);
        }
    }
}
