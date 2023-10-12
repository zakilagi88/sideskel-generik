<?php

namespace Database\Seeders;

use App\Models\kecamatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            [
                'kec_id' => '6371010',
                'kec_nama' => 'Banjarmasin Selatan',
                'kabkota_id' => '6371',
            ],
            [
                'kec_id' => '6371020',
                'kec_nama' => 'Banjarmasin Timur',
                'kabkota_id' => '6371',
            ],
            [
                'kec_id' => '6371030',
                'kec_nama' => 'Banjarmasin Barat',
                'kabkota_id' => '6371',
            ],
            [
                'kec_id' => '6371031',
                'kec_nama' => 'Banjarmasin Tengah',
                'kabkota_id' => '6371',
            ],
            [
                'kec_id' => '6371040',
                'kec_nama' => 'Banjarmasin Utara',
                'kabkota_id' => '6371',
            ],
        ];

        foreach ($data as $item) {
            kecamatan::create($item);
        }
    }
}
