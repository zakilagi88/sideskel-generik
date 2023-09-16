<?php

namespace Database\Seeders;

use App\Models\RT;
use Illuminate\Database\Seeder;
use App\Models\RukunTetangga; // Ganti dengan namespace model Anda

class RukunTetanggaSeeder extends Seeder
{
    public function run()
    {
        $data = [

            // list rt 1 sampai dengan rt 10
            ['rt_nama' => 'RT 001'],
            ['rt_nama' => 'RT 002'],
            ['rt_nama' => 'RT 003'],
            ['rt_nama' => 'RT 004'],
            ['rt_nama' => 'RT 005'],
            ['rt_nama' => 'RT 006'],
            ['rt_nama' => 'RT 007'],
            ['rt_nama' => 'RT 008'],
            ['rt_nama' => 'RT 009'],
            ['rt_nama' => 'RT 010'],
            // Tambahkan data lain sesuai kebutuhan
        ];

        foreach ($data as $item) {
            RT::create($item);
        }
    }
}