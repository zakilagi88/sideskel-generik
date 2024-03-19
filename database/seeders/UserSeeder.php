<?php


namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat pengguna admin
        $admin = User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => null,
            'password' => Hash::make('admin'),
        ]);

        $admin_desa = User::factory()->create([
            'name' => 'Admin Desa',
            'username' => 'admin_desa',
            'email' => null,
            'password' => Hash::make('admin_desa'),
        ]);

        $this->call(ShieldSeeder::class);

        $admin->assignRole('Admin');
        $admin_desa->assignRole('Admin Desa');
    }
}
