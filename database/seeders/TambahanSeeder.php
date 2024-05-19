<?php

namespace Database\Seeders;

use App\Models\Tambahan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TambahanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'nama' => 'Gas LPG',
                'slug' => 'gas-lpg',
                'sasaran' => 'Penduduk',
                'keterangan' => 'Kepemilikan Gas LPG',
                'kategori' => json_encode(["3 KG", "5 KG", "12 KG"]),
                'tgl_mulai' => '2024-05-19',
                'tgl_selesai' => '2024-05-19',
                'status' => 1,
            ],
            [
                'id' => 2,
                'nama' => 'Kepemilikan Tanah',
                'slug' => 'kepemilikan-tanah',
                'sasaran' => 'Keluarga',
                'keterangan' => 'Tanah',
                'kategori' => json_encode(["1 HA", "2 HA", "3 HA", "4 HA", "Lainnnya"]),
                'tgl_mulai' => '2024-05-19',
                'tgl_selesai' => '2024-05-19',
                'status' => 1,
            ]
        ];

        foreach ($data as $item) {
            Tambahan::create($item);
        }
    }
}
