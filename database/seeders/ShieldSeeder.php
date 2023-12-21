<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;

class ShieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_authentication::log","view_any_authentication::log","create_authentication::log","update_authentication::log","restore_authentication::log","restore_any_authentication::log","replicate_authentication::log","reorder_authentication::log","delete_authentication::log","delete_any_authentication::log","force_delete_authentication::log","force_delete_any_authentication::log","view_kartukeluarga","view_any_kartukeluarga","create_kartukeluarga","update_kartukeluarga","restore_kartukeluarga","restore_any_kartukeluarga","replicate_kartukeluarga","reorder_kartukeluarga","delete_kartukeluarga","delete_any_kartukeluarga","force_delete_kartukeluarga","force_delete_any_kartukeluarga","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","restore_penduduk","restore_any_penduduk","replicate_penduduk","reorder_penduduk","delete_penduduk","delete_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","view_r::w","view_any_r::w","create_r::w","update_r::w","restore_r::w","restore_any_r::w","replicate_r::w","reorder_r::w","delete_r::w","delete_any_r::w","force_delete_r::w","force_delete_any_r::w","view_rt","view_any_rt","create_rt","update_rt","restore_rt","restore_any_rt","replicate_rt","reorder_rt","delete_rt","delete_any_rt","force_delete_rt","force_delete_any_rt","view_s::l::s","view_any_s::l::s","create_s::l::s","update_s::l::s","restore_s::l::s","restore_any_s::l::s","replicate_s::l::s","reorder_s::l::s","delete_s::l::s","delete_any_s::l::s","force_delete_s::l::s","force_delete_any_s::l::s","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","widget_PendudukApexBarChart","widget_PendudukChart","widget_StatsOverview"]},{"name":"RT","guard_name":"web","permissions":["view_kartukeluarga","view_any_kartukeluarga","create_kartukeluarga","update_kartukeluarga","restore_kartukeluarga","restore_any_kartukeluarga","replicate_kartukeluarga","reorder_kartukeluarga","delete_kartukeluarga","delete_any_kartukeluarga","force_delete_kartukeluarga","force_delete_any_kartukeluarga","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","restore_penduduk","restore_any_penduduk","replicate_penduduk","reorder_penduduk","delete_penduduk","delete_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","view_r::w","view_any_r::w","create_r::w","update_r::w","restore_r::w","restore_any_r::w","replicate_r::w","reorder_r::w","delete_r::w","delete_any_r::w","force_delete_r::w","force_delete_any_r::w","view_rt","view_any_rt","create_rt","update_rt","restore_rt","restore_any_rt","replicate_rt","reorder_rt","delete_rt","delete_any_rt","force_delete_rt","force_delete_any_rt","view_s::l::s","view_any_s::l::s","create_s::l::s","update_s::l::s","restore_s::l::s","restore_any_s::l::s","replicate_s::l::s","reorder_s::l::s","delete_s::l::s","delete_any_s::l::s","force_delete_s::l::s","force_delete_any_s::l::s","widget_StatsOverview","page_MyProfilePage"]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = Utils::getRoleModel()::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name']
                ]);

                if (!blank($rolePlusPermission['permissions'])) {

                    $permissionModels = collect();

                    collect($rolePlusPermission['permissions'])
                        ->each(function ($permission) use ($permissionModels) {
                            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                                'name' => $permission,
                                'guard_name' => 'web'
                            ]));
                        });
                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {

            foreach ($permissions as $permission) {

                if (Utils::getPermissionModel()::whereName($permission)->doesntExist()) {
                    Utils::getPermissionModel()::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
