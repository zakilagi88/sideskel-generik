<?php

namespace Database\Seeders;

use App\Models\Deskel\Lembaga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Badan Permusyawaratan Desa',
                'slug' => 'badan-permusyawaratan-desa',
                'deskripsi' => 'Badan Permusyawaratan Desa (BPD) adalah lembaga yang berkedudukan di desa dan bersifat representatif yang berfungsi sebagai lembaga perwakilan rakyat desa.',
                'singkatan' => 'BPD',
                'logo_url' => '',
                'alamat' => 'Jl. Raya Desa No. 1',
                'kategori_jabatan' => [
                    'Ketua BPD',
                    'Wakil Ketua BPD',
                    'Sekretaris BPD',
                    'Bendahara BPD',
                    'Anggota BPD'
                ],
                'dokumen_id' => null,
            ],
            [
                'nama' => 'Karang Taruna',
                'slug' => 'karang-taruna',
                'deskripsi' => 'Karang Taruna adalah organisasi kepemudaan yang berbadan hukum dan berbentuk organisasi sosial kemasyarakatan yang bergerak di bidang sosial, ekonomi, budaya, dan lingkungan hidup.',
                'singkatan' => 'KT',
                'logo_url' => '',
                'alamat' => 'Jl. Raya Desa No. 2',
                'kategori_jabatan' => [
                    'Ketua Karang Taruna',
                    'Wakil Ketua Karang Taruna',
                    'Sekretaris Karang Taruna',
                    'Bendahara Karang Taruna',
                    'Anggota Karang Taruna'
                ],
                'dokumen_id' => null
            ]
        ];

        foreach ($data as $lembaga) {
            Lembaga::create($lembaga);
        }
    }
}
