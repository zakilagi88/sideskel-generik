<?php

namespace Database\Seeders;

use App\Models\SLS;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSlsRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user2 = User::find(2);
        $slsForRW1 = SLS::where('rw_id', 1)->get();
        $user2->slsRoles()->attach($slsForRW1, ['role_id' => 2]);

        // Assign peran RW (role_id = 2) ke user_id 6 dan semua sls_id dengan rw_id 2
        $user3 = User::find(3);
        $slsForRW2 = SLS::where('rw_id', 2)->get();
        $user3->slsRoles()->attach($slsForRW2, ['role_id' => 2]);

        $user_rt = [
            [
                'user_id' => 4,
                'sls_id' => 1,
                'role_id' => 3,
            ],
            [
                'user_id' => 5,
                'sls_id' => 2,
                'role_id' => 3,
            ],
            [
                'user_id' => 6,
                'sls_id' => 3,
                'role_id' => 3,
            ],
            [
                'user_id' => 7,
                'sls_id' => 4,
                'role_id' => 3,
            ],
            [
                'user_id' => 8,
                'sls_id' => 5,
                'role_id' => 3,
            ],
            [
                'user_id' => 9,
                'sls_id' => 6,
                'role_id' => 3,
            ],
            [
                'user_id' => 10,
                'sls_id' => 7,
                'role_id' => 3,
            ],
            [
                'user_id' => 11,
                'sls_id' => 8,
                'role_id' => 3,
            ],
            [
                'user_id' => 12,
                'sls_id' => 9,
                'role_id' => 3,
            ],
            [
                'user_id' => 13,
                'sls_id' => 10,
                'role_id' => 3,
            ],
            [
                'user_id' => 14,
                'sls_id' => 11,
                'role_id' => 3,
            ],
            [
                'user_id' => 15,
                'sls_id' => 12,
                'role_id' => 3,
            ],
            [
                'user_id' => 16,
                'sls_id' => 13,
                'role_id' => 3,
            ],
            [
                'user_id' => 17,
                'sls_id' => 14,
                'role_id' => 3,
            ],
            [
                'user_id' => 18,
                'sls_id' => 15,
                'role_id' => 3,
            ],
            [
                'user_id' => 19,
                'sls_id' => 16,
                'role_id' => 3,
            ],
            [
                'user_id' => 20,
                'sls_id' => 17,
                'role_id' => 3,
            ],
            [
                'user_id' => 21,
                'sls_id' => 18,
                'role_id' => 3,
            ],
            [
                'user_id' => 22,
                'sls_id' => 19,
                'role_id' => 3,
            ],
            [
                'user_id' => 23,
                'sls_id' => 20,
                'role_id' => 3,
            ],
            [
                'user_id' => 24,
                'sls_id' => 21,
                'role_id' => 3,
            ],
            [
                'user_id' => 25,
                'sls_id' => 22,
                'role_id' => 3,
            ],
            [
                'user_id' => 26,
                'sls_id' => 23,
                'role_id' => 3,
            ],
            [
                'user_id' => 27,
                'sls_id' => 24,
                'role_id' => 3,
            ],
            [
                'user_id' => 28,
                'sls_id' => 25,
                'role_id' => 3,
            ],
            [
                'user_id' => 29,
                'sls_id' => 26,
                'role_id' => 3,
            ],
            [
                'user_id' => 30,
                'sls_id' => 27,
                'role_id' => 3,
            ],
            [
                'user_id' => 31,
                'sls_id' => 28,
                'role_id' => 3,
            ],
            [
                'user_id' => 32,
                'sls_id' => 29,
                'role_id' => 3,
            ],
            [
                'user_id' => 33,
                'sls_id' => 30,
                'role_id' => 3,
            ],
            [
                'user_id' => 34,
                'sls_id' => 31,
                'role_id' => 3,
            ],
            [
                'user_id' => 35,
                'sls_id' => 32,
                'role_id' => 3,
            ],
            [
                'user_id' => 36,
                'sls_id' => 33,
                'role_id' => 3,
            ],
            [
                'user_id' => 37,
                'sls_id' => 34,
                'role_id' => 3,
            ],
            [
                'user_id' => 38,
                'sls_id' => 35,
                'role_id' => 3,
            ],
            [
                'user_id' => 39,
                'sls_id' => 36,
                'role_id' => 3,
            ],
        ];

        foreach ($user_rt as $user) {
            $user_id = $user['user_id'];
            $sls_id = $user['sls_id'];
            $role_id = $user['role_id'];

            $user = User::find($user_id);
            $sls = SLS::where('sls_id', $sls_id)->first();
            $user->slsRoles()->attach($sls, ['role_id' => $role_id]);
        }
    }
}
