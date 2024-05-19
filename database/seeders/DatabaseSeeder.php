<?php

use Database\Seeders\BeritaSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\JabatanSeeder;
use Database\Seeders\KeamananLingkunganSeeder;
use Database\Seeders\LembagaSeeder;
use Database\Seeders\PotensiSDASeeder;
use Database\Seeders\SaranaPrasaranaSeeder;
use Database\Seeders\StatKategorisSeeder;
use Database\Seeders\StatSDMSeeder;
use Database\Seeders\TambahanSeeder;
use Database\Seeders\UserSeeder;
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
            JabatanSeeder::class,
            StatKategorisSeeder::class,
            StatSDMSeeder::class,
            SaranaPrasaranaSeeder::class,
            PotensiSDASeeder::class,
            KeamananLingkunganSeeder::class,
            LembagaSeeder::class,
            TambahanSeeder::class,
            BeritaSeeder::class,
        ]);
    }
}
