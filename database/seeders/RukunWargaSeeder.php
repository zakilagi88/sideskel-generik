<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RW;

class RukunWargaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['rw_nama' => 'RW 001'],
            ['rw_nama' => 'RW 002'],
            ['rw_nama' => 'RW 003'],
            ['rw_nama' => 'RW 004'],
            ['rw_nama' => 'RW 005'],
            ['rw_nama' => 'RW 006'],
            ['rw_nama' => 'RW 007'],
            ['rw_nama' => 'RW 008'],
            ['rw_nama' => 'RW 009'],
            ['rw_nama' => 'RW 010'],
        ];

        foreach ($data as $item) {
            RW::create($item);
        }
    }
}
