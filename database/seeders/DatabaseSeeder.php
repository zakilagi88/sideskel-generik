<?php

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\KelurahanSeeder;
use Database\Seeders\RukunTetanggaSeeder;
use Database\Seeders\RukunWargaSeeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@test.com',
        ]);
        User::factory()->create([
            'name' => 'user',
            'email' => 'user@test.com',
        ]);

        $role = Role::create(['name' => 'Admin']);
        $user->assignRole($role);

        $this->call([
            KelurahanSeeder::class,
            RukunWargaSeeder::class,
            RukunTetanggaSeeder::class
        ]);

        $kartuKeluargaCount = 5; // Jumlah kartu keluarga yang akan dibuat
        $pendudukCountPerKK = 2; // Jumlah penduduk per kartu keluarga

        for ($i = 1; $i <= $kartuKeluargaCount; $i++) {
            $kartuKeluarga = KartuKeluarga::factory()->create([
                'kk_id' => $i,
            ]);

            Penduduk::factory()
                ->count($pendudukCountPerKK)
                ->for($kartuKeluarga, 'kartuKeluarga')
                ->create();
        }
    }
}
