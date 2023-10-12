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

            ['sls_kode' => '0001', 'sls_nama' => 'RT 001/RW 001', 'rw_id' => 1, 'rt_id' => 1, 'kel_id' => '6371020005'],
            ['sls_kode' => '0002', 'sls_nama' => 'RT 002/RW 001', 'rw_id' => 1, 'rt_id' => 2, 'kel_id' => '6371020005'],
            ['sls_kode' => '0003', 'sls_nama' => 'RT 003/RW 001', 'rw_id' => 1, 'rt_id' => 3, 'kel_id' => '6371020005'],
            ['sls_kode' => '0004', 'sls_nama' => 'RT 004/RW 001', 'rw_id' => 1, 'rt_id' => 4, 'kel_id' => '6371020005'],
            ['sls_kode' => '0005', 'sls_nama' => 'RT 005/RW 001', 'rw_id' => 1, 'rt_id' => 5, 'kel_id' => '6371020005'],
            ['sls_kode' => '0006', 'sls_nama' => 'RT 006/RW 001', 'rw_id' => 1, 'rt_id' => 6, 'kel_id' => '6371020005'],
            ['sls_kode' => '0007', 'sls_nama' => 'RT 007/RW 001', 'rw_id' => 1, 'rt_id' => 7, 'kel_id' => '6371020005'],
            ['sls_kode' => '0008', 'sls_nama' => 'RT 008/RW 001', 'rw_id' => 1, 'rt_id' => 8, 'kel_id' => '6371020005'],
            ['sls_kode' => '0009', 'sls_nama' => 'RT 009/RW 001', 'rw_id' => 1, 'rt_id' => 9, 'kel_id' => '6371020005'],
            ['sls_kode' => '0010', 'sls_nama' => 'RT 010/RW 001', 'rw_id' => 1, 'rt_id' => 10, 'kel_id' => '6371020005'],
            ['sls_kode' => '0011', 'sls_nama' => 'RT 011/RW 001', 'rw_id' => 1, 'rt_id' => 11, 'kel_id' => '6371020005'],
            ['sls_kode' => '0012', 'sls_nama' => 'RT 012/RW 001', 'rw_id' => 1, 'rt_id' => 12, 'kel_id' => '6371020005'],
            ['sls_kode' => '0013', 'sls_nama' => 'RT 013/RW 001', 'rw_id' => 1, 'rt_id' => 13, 'kel_id' => '6371020005'],
            ['sls_kode' => '0014', 'sls_nama' => 'RT 014/RW 001', 'rw_id' => 1, 'rt_id' => 14, 'kel_id' => '6371020005'],
            ['sls_kode' => '0015', 'sls_nama' => 'RT 015/RW 001', 'rw_id' => 1, 'rt_id' => 15, 'kel_id' => '6371020005'],
            ['sls_kode' => '0016', 'sls_nama' => 'RT 016/RW 001', 'rw_id' => 1, 'rt_id' => 16, 'kel_id' => '6371020005'],
            ['sls_kode' => '0017', 'sls_nama' => 'RT 017/RW 002', 'rw_id' => 2, 'rt_id' => 17, 'kel_id' => '6371020005'],
            ['sls_kode' => '0018', 'sls_nama' => 'RT 018/RW 002', 'rw_id' => 2, 'rt_id' => 18, 'kel_id' => '6371020005'],
            ['sls_kode' => '0019', 'sls_nama' => 'RT 019/RW 002', 'rw_id' => 2, 'rt_id' => 19, 'kel_id' => '6371020005'],
            ['sls_kode' => '0020', 'sls_nama' => 'RT 020/RW 002', 'rw_id' => 2, 'rt_id' => 20, 'kel_id' => '6371020005'],
            ['sls_kode' => '0021', 'sls_nama' => 'RT 021/RW 002', 'rw_id' => 2, 'rt_id' => 21, 'kel_id' => '6371020005'],
            ['sls_kode' => '0022', 'sls_nama' => 'RT 022/RW 002', 'rw_id' => 2, 'rt_id' => 22, 'kel_id' => '6371020005'],
            ['sls_kode' => '0023', 'sls_nama' => 'RT 023/RW 002', 'rw_id' => 2, 'rt_id' => 23, 'kel_id' => '6371020005'],
            ['sls_kode' => '0024', 'sls_nama' => 'RT 024/RW 002', 'rw_id' => 2, 'rt_id' => 24, 'kel_id' => '6371020005'],
            ['sls_kode' => '0025', 'sls_nama' => 'RT 025/RW 002', 'rw_id' => 2, 'rt_id' => 25, 'kel_id' => '6371020005'],
            ['sls_kode' => '0026', 'sls_nama' => 'RT 026/RW 002', 'rw_id' => 2, 'rt_id' => 26, 'kel_id' => '6371020005'],
            ['sls_kode' => '0027', 'sls_nama' => 'RT 027/RW 002', 'rw_id' => 2, 'rt_id' => 27, 'kel_id' => '6371020005'],
            ['sls_kode' => '0028', 'sls_nama' => 'RT 028/RW 002', 'rw_id' => 2, 'rt_id' => 28, 'kel_id' => '6371020005'],
            ['sls_kode' => '0029', 'sls_nama' => 'RT 029/RW 002', 'rw_id' => 2, 'rt_id' => 29, 'kel_id' => '6371020005'],
            ['sls_kode' => '0030', 'sls_nama' => 'RT 030/RW 002', 'rw_id' => 2, 'rt_id' => 30, 'kel_id' => '6371020005'],
            ['sls_kode' => '0031', 'sls_nama' => 'RT 031/RW 002', 'rw_id' => 2, 'rt_id' => 31, 'kel_id' => '6371020005'],
            ['sls_kode' => '0032', 'sls_nama' => 'RT 032/RW 002', 'rw_id' => 2, 'rt_id' => 32, 'kel_id' => '6371020005'],
            ['sls_kode' => '0033', 'sls_nama' => 'RT 033/RW 002', 'rw_id' => 2, 'rt_id' => 33, 'kel_id' => '6371020005'],
            ['sls_kode' => '0034', 'sls_nama' => 'RT 034/RW 002', 'rw_id' => 2, 'rt_id' => 34, 'kel_id' => '6371020005'],
            ['sls_kode' => '0035', 'sls_nama' => 'RT 035/RW 002', 'rw_id' => 2, 'rt_id' => 35, 'kel_id' => '6371020005'],
            ['sls_kode' => '0036', 'sls_nama' => 'RT 036/RW 002', 'rw_id' => 2, 'rt_id' => 36, 'kel_id' => '6371020005'],

        ];
        foreach ($data as $item) {
            SLS::create($item);
        }
    }
}