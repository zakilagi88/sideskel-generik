<?php

use App\Models\AnggotaKeluarga;
use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;
use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\AnggotaKeluargaSeeder;
use Database\Seeders\KabKotaSeeder;
use Database\Seeders\KartuKeluargaSeeder;
use Database\Seeders\KecamatanSeeder;
use Database\Seeders\KelurahanSeeder;
use Database\Seeders\PendudukSeeder;
use Database\Seeders\ProvinsiSeeder;
use Database\Seeders\RukunTetanggaSeeder;
use Database\Seeders\RukunWargaSeeder;
use Database\Seeders\ShieldSeeder;
use Database\Seeders\SLSSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\UserSlsRolesSeeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RukunWargaSeeder::class,
            RukunTetanggaSeeder::class,
            SLSSeeder::class,
            ShieldSeeder::class,
            UserSlsRolesSeeder::class,

        ]);
    }
}
