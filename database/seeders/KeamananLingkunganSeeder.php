<?php

namespace Database\Seeders;

use App\Models\KeamananDanLingkungan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KeamananLingkunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            "keamanan-dan-ketertiban" => [

                "jumlah_pos_kamling" => "0",
                "jumlah_anggota_linmas" => "0",
                "jumlah_operasi_penertiban" => "0",
                "jumlah_kejadian_kriminalitas" => [
                    [
                        "jenis_kejadian_kriminalitas" => "Pencurian",
                        "jumlah_kejadian_kriminalitas" => null
                    ],

                ],
            ],
            "lingkungan-hidup" => [
                "wabah_penyakit_menular" => "0",
                "jumlah_pos_bencana_alam" => "0",
                "jumlah_pos_hutan_lindung" => "0",
                "jumlah_lokasi_pencemaran_tanah" => "0",
                "jumlah_kejadian_bencana" => [
                    [
                        "jenis_kejadian_bencana" => "Gempa Bumi",
                        "jumlah_kejadian_bencana" => null
                    ]
                ],

            ]
        ];

        KeamananDanLingkungan::create([
            'deskel_profil_id' => 1,
            'jenis' => 'keamanan-dan-ketertiban',
            'data' => array($data['keamanan-dan-ketertiban'])
        ]);

        KeamananDanLingkungan::create([
            'deskel_profil_id' => 1,
            'jenis' => 'lingkungan-hidup',
            'data' => array($data['lingkungan-hidup'])
        ]);
    }
}
