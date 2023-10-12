<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelurahan; // Ganti ini sesuai dengan namespace model Anda

class KelurahanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'kel_id' => '6371020001',
                'kel_nama' => 'Pekapuran Raya',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020002',
                'kel_nama' => 'Karang Mekar',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020003',
                'kel_nama' => 'Kebun Bunga',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020004',
                'kel_nama' => 'Sungai Lulut',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020005',
                'kel_nama' => 'Kuripan',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020011',
                'kel_nama' => 'Sungai Bilu',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020012',
                'kel_nama' => 'Pengambangan',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020013',
                'kel_nama' => 'Banua Anyar',
                'kec_id' => '6371020',
            ],
            [
                'kel_id' => '6371020014',
                'kel_nama' => 'Pemurus Luar',
                'kec_id' => '6371020',
            ],
        ];

        foreach ($data as $item) {
            Kelurahan::create($item);
        }
    }
}