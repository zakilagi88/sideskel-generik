<?php

namespace Database\Seeders;

use App\Models\Deskel\Jabatan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jabatans = [
            [
                'nama' => 'Kepala Desa',
                'tupoksi' => 'Mengatur dan mengelola pemerintahan desa',
            ],
            [
                'nama' => 'Sekretaris Desa',
                'tupoksi' => 'Membantu Kepala Desa dalam mengatur dan mengelola pemerintahan desa',
            ],
            [
                'nama' => 'Kepala Urusan Keuangan',
                'tupoksi' => 'Mengatur dan mengelola keuangan desa',
            ],
            [
                'nama' => 'Kepala Urusan Perencanaan',
                'tupoksi' => 'Mengatur dan mengelola perencanaan pembangunan desa',
            ],
            [
                'nama' => 'Kepala Urusan Kepegawaian',
                'tupoksi' => 'Mengatur dan mengelola kepegawaian desa',
            ],
            [
                'nama' => 'Kepala Urusan Pemerintahan',
                'tupoksi' => 'Mengatur dan mengelola pemerintahan desa',
            ],
            [
                'nama' => 'Kepala Urusan Umum',
                'tupoksi' => 'Mengatur dan mengelola umum desa',
            ],
            [
                'nama' => 'Kepala Urusan Pembangunan',
                'tupoksi' => 'Mengatur dan mengelola pembangunan desa',
            ],
            [
                'nama' => 'Kepala Urusan Kesra',
                'tupoksi' => 'Mengatur dan mengelola kesra desa',
            ],
            [
                'nama' => 'Kepala Urusan Pelayanan',
                'tupoksi' => 'Mengatur dan mengelola pelayanan desa',
            ],
            [
                'nama' => 'Kepala Urusan Hukum',
                'tupoksi' => 'Mengatur dan mengelola hukum desa',
            ],
            [
                'nama' => 'Kepala Urusan Lingkungan',
                'tupoksi' => 'Mengatur dan mengelola lingkungan desa',
            ],
        ];

        Jabatan::insert($jabatans);
    }
}
