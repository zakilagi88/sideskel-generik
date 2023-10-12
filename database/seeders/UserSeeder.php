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
            'name' => 'admin',
            'email' => 'admin@kuripan.id',
            'password' => Hash::make('kuripan'),
        ]);

        $adminRole = Role::create(['name' => 'Admin']);
        $admin->assignRole($adminRole);

        // Perulangan untuk membuat RW 001 dan RW 002
        for ($rwIndex = 1; $rwIndex <= 2; $rwIndex++) {
            $rwEmail = "RW" . str_pad($rwIndex, 3, '0', STR_PAD_LEFT) . "@kuripan.id";

            $rwUser = User::factory()->create([
                'name' => "RW " . str_pad($rwIndex, 3, '0', STR_PAD_LEFT),
                'email' => $rwEmail,
                'password' => Hash::make('kuripan'),
            ]);

            $rwRole = Role::where('name', 'RW')->where('guard_name', 'web')->first();

            if (!$rwRole) {
                $rwRole = Role::create(['name' => 'RW']);
            }

            $rwUser->assignRole($rwRole);
        }

        // Membuat RT 001-RT 016 untuk RW 001
        for ($rtIndex = 1; $rtIndex <= 16; $rtIndex++) {
            $rtEmail = "RT" . str_pad($rtIndex, 3, '0', STR_PAD_LEFT) . "_RW001@kuripan.id";

            $rtUser = User::factory()->create([
                'name' => "RT " . str_pad($rtIndex, 3, '0', STR_PAD_LEFT) . "/RW 001",
                'email' => $rtEmail,
                'password' => Hash::make('kuripan'),
            ]);

            $rtRole = Role::where('name', 'RT')->where('guard_name', 'web')->first();

            if (!$rtRole) {
                $rtRole = Role::create(['name' => 'RT']);
            }

            $rtUser->assignRole($rtRole);
        }

        // Membuat RT 017-RT 036 untuk RW 002
        for ($rtIndex = 17; $rtIndex <= 36; $rtIndex++) {
            $rtEmail = "RT" . str_pad($rtIndex, 3, '0', STR_PAD_LEFT) . "_RW002@kuripan.id";

            $rtUser = User::factory()->create([
                'name' => "RT " . str_pad($rtIndex, 3, '0', STR_PAD_LEFT) . "/RW 002",
                'email' => $rtEmail,
                'password' => Hash::make('kuripan'),
            ]);

            $rtRole = Role::where('name', 'RT')->where('guard_name', 'web')->first();

            if (!$rtRole) {
                $rtRole = Role::create(['name' => 'RT']);
            }

            $rtUser->assignRole($rtRole);
        }
    }
}
