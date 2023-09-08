<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelurahan; // Ganti ini sesuai dengan namespace model Anda

class KelurahanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['kelurahan_kode' => '6371020001', 'kelurahan_nama' => 'Pekapuran Raya'],
            ['kelurahan_kode' => '6371020002', 'kelurahan_nama' => 'Karang Mekar'],
            ['kelurahan_kode' => '6371020003', 'kelurahan_nama' => 'Kebun Bunga'],
        ];

        foreach ($data as $item) {
            Kelurahan::create($item);
        }
    }
}