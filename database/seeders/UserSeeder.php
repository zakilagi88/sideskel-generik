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
        $admin = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => null,
            'password' => Hash::make('admin'),
        ]);

        $admin_web = User::factory()->create([
            'name' => 'Admin Web',
            'username' => 'admin_web',
            'email' => null,
            'password' => Hash::make('admin_web'),
        ]);

        $this->call(ShieldSeeder::class);

        $admin->assignRole('Admin');
        $admin_web->assignRole('Admin Web');
    }
}
