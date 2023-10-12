<?php

namespace Database\Seeders;

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AnggotaKeluargaSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua kartu keluarga yang ada
        $kartuKeluargas = KartuKeluarga::all();

        $kartuKeluargas->each(function ($kartuKeluarga) {
            $niks = Penduduk::whereDoesntHave('anggotaKeluarga', function ($query) use ($kartuKeluarga) {
                return $query->where('kk_id', $kartuKeluarga->kk_id);
            })->pluck('nik')->toArray();

            // Buat data AnggotaKeluarga dengan factory
            $anggotaKeluarga = AnggotaKeluarga::factory()
                ->count(4) // Sesuaikan dengan jumlah anggota keluarga yang ingin Anda buat
                ->state(new Sequence(
                    ['hubungan' => 'Kepala Keluarga'],
                    ['hubungan' => 'Istri'],
                    ['hubungan' => 'Anak'],
                    ['hubungan' => 'Famili Lain'],
                ))
                ->state(function () use ($kartuKeluarga, $niks) {
                    $hubungan = Arr::random(['Kepala Keluarga', 'Istri', 'Anak', 'Famili Lain']);
                    $nik = Arr::random($niks);

                    return [
                        'nik' => $nik,
                        'kk_id' => $kartuKeluarga->kk_id,
                    ];
                })
                ->create();
            if (!$kartuKeluarga->kk_kepala) {
                $kepalaKeluarga = $anggotaKeluarga->where('hubungan', 'Kepala Keluarga')->first();
                if ($kepalaKeluarga) {
                    $kartuKeluarga->kk_kepala = $kepalaKeluarga->nik;
                    $kartuKeluarga->save();
                }
            }
        });
    }
}