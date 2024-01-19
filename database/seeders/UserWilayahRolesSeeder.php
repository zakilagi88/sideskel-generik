<?php

namespace Database\Seeders;

use App\Models\Wilayah;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserWilayahRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user2 = User::find(2);
        $WilayahForRW1 = Wilayah::where('rw_id', 1)->get();
        $user2->wilayahRoles()->attach($WilayahForRW1, ['role_id' => 2]);

        // Assign peran RW (role_id = 2) ke user_id 6 dan semua wilayah_id dengan rw_id 2
        $user3 = User::find(3);
        $WilayahForRW2 = Wilayah::where('rw_id', 2)->get();
        $user3->wilayahRoles()->attach($WilayahForRW2, ['role_id' => 2]);

        $user_rt = [
            [
                'user_id' => 4,
                'wilayah_id' => 1,
                'role_id' => 3,
            ],
            [
                'user_id' => 5,
                'wilayah_id' => 2,
                'role_id' => 3,
            ],
            [
                'user_id' => 6,
                'wilayah_id' => 3,
                'role_id' => 3,
            ],
            [
                'user_id' => 7,
                'wilayah_id' => 4,
                'role_id' => 3,
            ],
            [
                'user_id' => 8,
                'wilayah_id' => 5,
                'role_id' => 3,
            ],
            [
                'user_id' => 9,
                'wilayah_id' => 6,
                'role_id' => 3,
            ],
            [
                'user_id' => 10,
                'wilayah_id' => 7,
                'role_id' => 3,
            ],
            [
                'user_id' => 11,
                'wilayah_id' => 8,
                'role_id' => 3,
            ],
            [
                'user_id' => 12,
                'wilayah_id' => 9,
                'role_id' => 3,
            ],
            [
                'user_id' => 13,
                'wilayah_id' => 10,
                'role_id' => 3,
            ],
            [
                'user_id' => 14,
                'wilayah_id' => 11,
                'role_id' => 3,
            ],
            [
                'user_id' => 15,
                'wilayah_id' => 12,
                'role_id' => 3,
            ],
            [
                'user_id' => 16,
                'wilayah_id' => 13,
                'role_id' => 3,
            ],
            [
                'user_id' => 17,
                'wilayah_id' => 14,
                'role_id' => 3,
            ],
            [
                'user_id' => 18,
                'wilayah_id' => 15,
                'role_id' => 3,
            ],
            [
                'user_id' => 19,
                'wilayah_id' => 16,
                'role_id' => 3,
            ],
            [
                'user_id' => 20,
                'wilayah_id' => 17,
                'role_id' => 3,
            ],
            [
                'user_id' => 21,
                'wilayah_id' => 18,
                'role_id' => 3,
            ],
            [
                'user_id' => 22,
                'wilayah_id' => 19,
                'role_id' => 3,
            ],
            [
                'user_id' => 23,
                'wilayah_id' => 20,
                'role_id' => 3,
            ],
            [
                'user_id' => 24,
                'wilayah_id' => 21,
                'role_id' => 3,
            ],
            [
                'user_id' => 25,
                'wilayah_id' => 22,
                'role_id' => 3,
            ],
            [
                'user_id' => 26,
                'wilayah_id' => 23,
                'role_id' => 3,
            ],
            [
                'user_id' => 27,
                'wilayah_id' => 24,
                'role_id' => 3,
            ],
            [
                'user_id' => 28,
                'wilayah_id' => 25,
                'role_id' => 3,
            ],
            [
                'user_id' => 29,
                'wilayah_id' => 26,
                'role_id' => 3,
            ],
            [
                'user_id' => 30,
                'wilayah_id' => 27,
                'role_id' => 3,
            ],
            [
                'user_id' => 31,
                'wilayah_id' => 28,
                'role_id' => 3,
            ],
            [
                'user_id' => 32,
                'wilayah_id' => 29,
                'role_id' => 3,
            ],
            [
                'user_id' => 33,
                'wilayah_id' => 30,
                'role_id' => 3,
            ],
            [
                'user_id' => 34,
                'wilayah_id' => 31,
                'role_id' => 3,
            ],
            [
                'user_id' => 35,
                'wilayah_id' => 32,
                'role_id' => 3,
            ],
            [
                'user_id' => 36,
                'wilayah_id' => 33,
                'role_id' => 3,
            ],
            [
                'user_id' => 37,
                'wilayah_id' => 34,
                'role_id' => 3,
            ],
            [
                'user_id' => 38,
                'wilayah_id' => 35,
                'role_id' => 3,
            ],
            [
                'user_id' => 39,
                'wilayah_id' => 36,
                'role_id' => 3,
            ],
        ];

        foreach ($user_rt as $user) {
            $user_id = $user['user_id'];
            $wilayah_id = $user['wilayah_id'];
            $role_id = $user['role_id'];

            $user = User::find($user_id);
            $Wilayah = Wilayah::where('wilayah_id', $wilayah_id)->first();
            $user->WilayahRoles()->attach($Wilayah, ['role_id' => $role_id]);
        }
    }
}