<?php

namespace Database\Seeders;

use App\Models\SaranaPrasarana;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaranaPrasaranaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_tables = config('app_data.default_tables');

        foreach ($default_tables['sarana_prasarana'] as $jenis => $value) {
            SaranaPrasarana::updateOrCreate(
                ['jenis' => $jenis],
                [
                    'deskel_profil_id' => 1,
                    'jenis' => $jenis,
                    'data' => array_map(function ($item) {
                        return [
                            'nama' => $item[0],
                            'jumlah' => $item[1],
                            'satuan' => $item[2],
                        ];
                    }, $value),
                ]
            );
        }
    }
}