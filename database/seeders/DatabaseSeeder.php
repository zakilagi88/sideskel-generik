<?php

use App\Models\KartuKeluarga;
use App\Models\Penduduk;
use App\Models\RT;
use App\Models\RW;
use App\Models\SLS;
use Illuminate\Database\Seeder;
use App\Models\User;
use Database\Seeders\KelurahanSeeder;
use Database\Seeders\RukunTetanggaSeeder;
use Database\Seeders\RukunWargaSeeder;
use Database\Seeders\SLSSeeder;
use Illuminate\Support\Arr;
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
            RukunTetanggaSeeder::class,
            SLSSeeder::class
        ]);
    
        $kk = KartuKeluarga::factory(5)
            ->has(Penduduk::factory()->count(3), 'penduduks')
            ->create();

        // SLS::factory()->count(10)->create();
    }
}