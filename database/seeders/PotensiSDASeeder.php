<?php

namespace Database\Seeders;

use App\Models\Desa\PotensiSDA;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PotensiSDASeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['potensi-sda'] as $jenis => $value) {
            PotensiSDA::updateOrCreate(
                ['jenis' => $jenis],
                [
                    'deskel_profil_id' => 1,
                    'jenis' => $jenis,
                    'data' => array_map(
                        function ($item) {
                            return [
                                'label' => $item['label'],
                                'entitas' => $item['entitas'],
                            ];
                        },
                        $value
                    ),
                ]
            );
        }
    }
}
