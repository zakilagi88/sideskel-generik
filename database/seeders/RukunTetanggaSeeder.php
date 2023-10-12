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
            ['rt_nama' => 'RT 011'],
            ['rt_nama' => 'RT 012'],
            ['rt_nama' => 'RT 013'],
            ['rt_nama' => 'RT 014'],
            ['rt_nama' => 'RT 015'],
            ['rt_nama' => 'RT 016'],
            ['rt_nama' => 'RT 017'],
            ['rt_nama' => 'RT 018'],
            ['rt_nama' => 'RT 019'],
            ['rt_nama' => 'RT 020'],
            ['rt_nama' => 'RT 021'],
            ['rt_nama' => 'RT 022'],
            ['rt_nama' => 'RT 023'],
            ['rt_nama' => 'RT 024'],
            ['rt_nama' => 'RT 025'],
            ['rt_nama' => 'RT 026'],
            ['rt_nama' => 'RT 027'],
            ['rt_nama' => 'RT 028'],
            ['rt_nama' => 'RT 029'],
            ['rt_nama' => 'RT 030'],
            ['rt_nama' => 'RT 031'],
            ['rt_nama' => 'RT 032'],
            ['rt_nama' => 'RT 033'],
            ['rt_nama' => 'RT 034'],
            ['rt_nama' => 'RT 035'],
            ['rt_nama' => 'RT 036'],
            ['rt_nama' => 'RT 037'],
            ['rt_nama' => 'RT 038'],
            ['rt_nama' => 'RT 039'],
            ['rt_nama' => 'RT 040'],


            // Tambahkan data lain sesuai kebutuhan
        ];

        foreach ($data as $item) {
            RT::create($item);
        }
    }
}
