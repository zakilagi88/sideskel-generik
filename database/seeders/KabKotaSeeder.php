<?php

namespace Database\Seeders;

use App\Models\Kab_Kota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KabKotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $data = [
            [
                'kabkota_id' => '6301',
                'kabkota_nama' => 'Kab. Tanah Laut',
                'prov_id' => '63',
            ],
            [
                'kabkota_id' => '6302',
                'kabkota_nama' => 'Kab. Kotabaru',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6303',
                'kabkota_nama' => 'Kab. Banjar',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6304',
                'kabkota_nama' => 'Kab. Barito Kuala',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6305',
                'kabkota_nama' => 'Kab. Tapin',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6306',
                'kabkota_nama' => 'Kab. Hulu Sungai Selatan',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6307',
                'kabkota_nama' => 'Kab. Hulu Sungai Tengah',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6308',
                'kabkota_nama' => 'Kab. Hulu Sungai Utara',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6309',
                'kabkota_nama' => 'Kab. Tabalong',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6310',
                'kabkota_nama' => 'Kab. Tanah Bumbu',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6311',
                'kabkota_nama' => 'Kab. Balangan',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6371',
                'kabkota_nama' => 'Kota Banjarmasin',
                'prov_id' => '63',

            ],
            [
                'kabkota_id' => '6372',
                'kabkota_nama' => 'Kota Banjarbaru',
                'prov_id' => '63',

            ],
        ];

        foreach ($data as $item) {
            Kab_Kota::create($item);
        }
    }
}
