<?php

namespace Database\Seeders;

use App\Models\SLS;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SLSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['sls_kode' => '0001', 'sls_nama' => 'RW 001 / RT 001', 'rw_id' => 1, 'rt_id' => 1],
            ['sls_kode' => '0002', 'sls_nama' => 'RW 002 / RT 001', 'rw_id' => 2, 'rt_id' => 1],
            ['sls_kode' => '0003', 'sls_nama' => 'RW 002 / RT 002', 'rw_id' => 2, 'rt_id' => 2],
            ['sls_kode' => '0004', 'sls_nama' => 'RW 003 / RT 001', 'rw_id' => 3, 'rt_id' => 1],
            ['sls_kode' => '0005', 'sls_nama' => 'RW 003 / RT 002', 'rw_id' => 3, 'rt_id' => 2],
            ['sls_kode' => '0006', 'sls_nama' => 'RW 003 / RT 003', 'rw_id' => 3, 'rt_id' => 3],
            ['sls_kode' => '0007', 'sls_nama' => 'RW 004 / RT 001', 'rw_id' => 4, 'rt_id' => 1],
            ['sls_kode' => '0008', 'sls_nama' => 'RW 004 / RT 002', 'rw_id' => 4, 'rt_id' => 2],
            ['sls_kode' => '0009', 'sls_nama' => 'RW 004 / RT 003', 'rw_id' => 4, 'rt_id' => 3],
            ['sls_kode' => '0010', 'sls_nama' => 'RW 004 / RT 004', 'rw_id' => 4, 'rt_id' => 4],
            ['sls_kode' => '0011', 'sls_nama' => 'RW 005 / RT 001', 'rw_id' => 5, 'rt_id' => 1],
            ['sls_kode' => '0012', 'sls_nama' => 'RW 005 / RT 002', 'rw_id' => 5, 'rt_id' => 2],
            ['sls_kode' => '0013', 'sls_nama' => 'RW 005 / RT 003', 'rw_id' => 5, 'rt_id' => 3],
            ['sls_kode' => '0014', 'sls_nama' => 'RW 005 / RT 004', 'rw_id' => 5, 'rt_id' => 4],
            ['sls_kode' => '0015', 'sls_nama' => 'RW 005 / RT 005', 'rw_id' => 5, 'rt_id' => 5],
            ['sls_kode' => '0016', 'sls_nama' => 'RW 006 / RT 001', 'rw_id' => 6, 'rt_id' => 1],
            ['sls_kode' => '0017', 'sls_nama' => 'RW 006 / RT 002', 'rw_id' => 6, 'rt_id' => 2],
            ['sls_kode' => '0018', 'sls_nama' => 'RW 006 / RT 003', 'rw_id' => 6, 'rt_id' => 3],
            ['sls_kode' => '0019', 'sls_nama' => 'RW 006 / RT 004', 'rw_id' => 6, 'rt_id' => 4],
            ['sls_kode' => '0020', 'sls_nama' => 'RW 006 / RT 005', 'rw_id' => 6, 'rt_id' => 5],
            ['sls_kode' => '0021', 'sls_nama' => 'RW 006 / RT 006', 'rw_id' => 6, 'rt_id' => 6],
            ['sls_kode' => '0022', 'sls_nama' => 'RW 007 / RT 001', 'rw_id' => 7, 'rt_id' => 1],
            ['sls_kode' => '0023', 'sls_nama' => 'RW 007 / RT 002', 'rw_id' => 7, 'rt_id' => 2],
            ['sls_kode' => '0024', 'sls_nama' => 'RW 007 / RT 003', 'rw_id' => 7, 'rt_id' => 3],
            ['sls_kode' => '0025', 'sls_nama' => 'RW 007 / RT 004', 'rw_id' => 7, 'rt_id' => 4],
            ['sls_kode' => '0026', 'sls_nama' => 'RW 007 / RT 005', 'rw_id' => 7, 'rt_id' => 5],
            ['sls_kode' => '0027', 'sls_nama' => 'RW 007 / RT 006', 'rw_id' => 7, 'rt_id' => 6],
            ['sls_kode' => '0028', 'sls_nama' => 'RW 007 / RT 007', 'rw_id' => 7, 'rt_id' => 7],
            ['sls_kode' => '0029', 'sls_nama' => 'RW 008 / RT 001', 'rw_id' => 8, 'rt_id' => 1],
            ['sls_kode' => '0030', 'sls_nama' => 'RW 008 / RT 002', 'rw_id' => 8, 'rt_id' => 2],
            ['sls_kode' => '0031', 'sls_nama' => 'RW 008 / RT 003', 'rw_id' => 8, 'rt_id' => 3],
            ['sls_kode' => '0032', 'sls_nama' => 'RW 008 / RT 004', 'rw_id' => 8, 'rt_id' => 4],
            ['sls_kode' => '0033', 'sls_nama' => 'RW 008 / RT 005', 'rw_id' => 8, 'rt_id' => 5],
            ['sls_kode' => '0034', 'sls_nama' => 'RW 008 / RT 006', 'rw_id' => 8, 'rt_id' => 6],
            ['sls_kode' => '0035', 'sls_nama' => 'RW 008 / RT 007', 'rw_id' => 8, 'rt_id' => 7],
            ['sls_kode' => '0036', 'sls_nama' => 'RW 008 / RT 008', 'rw_id' => 8, 'rt_id' => 8],
            ['sls_kode' => '0037', 'sls_nama' => 'RW 009 / RT 001', 'rw_id' => 9, 'rt_id' => 1],
            ['sls_kode' => '0038', 'sls_nama' => 'RW 009 / RT 002', 'rw_id' => 9, 'rt_id' => 2],
            ['sls_kode' => '0039', 'sls_nama' => 'RW 009 / RT 003', 'rw_id' => 9, 'rt_id' => 3],
            ['sls_kode' => '0040', 'sls_nama' => 'RW 009 / RT 004', 'rw_id' => 9, 'rt_id' => 4],
            ['sls_kode' => '0041', 'sls_nama' => 'RW 009 / RT 005', 'rw_id' => 9, 'rt_id' => 5],
            ['sls_kode' => '0042', 'sls_nama' => 'RW 009 / RT 006', 'rw_id' => 9, 'rt_id' => 6],
            ['sls_kode' => '0043', 'sls_nama' => 'RW 009 / RT 007', 'rw_id' => 9, 'rt_id' => 7],
            ['sls_kode' => '0044', 'sls_nama' => 'RW 009 / RT 008', 'rw_id' => 9, 'rt_id' => 8],
            ['sls_kode' => '0045', 'sls_nama' => 'RW 009 / RT 009', 'rw_id' => 9, 'rt_id' => 9],
            ['sls_kode' => '0046', 'sls_nama' => 'RW 010 / RT 001', 'rw_id' => 10, 'rt_id' => 1],
            ['sls_kode' => '0047', 'sls_nama' => 'RW 010 / RT 002', 'rw_id' => 10, 'rt_id' => 2],
            ['sls_kode' => '0048', 'sls_nama' => 'RW 010 / RT 003', 'rw_id' => 10, 'rt_id' => 3],
            ['sls_kode' => '0049', 'sls_nama' => 'RW 010 / RT 004', 'rw_id' => 10, 'rt_id' => 4],
            ['sls_kode' => '0050', 'sls_nama' => 'RW 010 / RT 005', 'rw_id' => 10, 'rt_id' => 5],
            ['sls_kode' => '0051', 'sls_nama' => 'RW 010 / RT 006', 'rw_id' => 10, 'rt_id' => 6],
            ['sls_kode' => '0052', 'sls_nama' => 'RW 010 / RT 007', 'rw_id' => 10, 'rt_id' => 7],
            ['sls_kode' => '0053', 'sls_nama' => 'RW 010 / RT 008', 'rw_id' => 10, 'rt_id' => 8],
            ['sls_kode' => '0054', 'sls_nama' => 'RW 010 / RT 009', 'rw_id' => 10, 'rt_id' => 9],
            ['sls_kode' => '0055', 'sls_nama' => 'RW 010 / RT 010', 'rw_id' => 10, 'rt_id' => 10],


        ];
        foreach ($data as $item) {
            SLS::create($item);
        }
    }
}
