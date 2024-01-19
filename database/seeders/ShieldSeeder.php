<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions =
            '[
            {
                "name": "super_admin",
                "guard_name": "web",
                "permissions":
                    [
                    "view_authentication::log",
                    "view_any_authentication::log",
                    "create_authentication::log",
                    "update_authentication::log",
                    "restore_authentication::log",
                    "restore_any_authentication::log",
                    "replicate_authentication::log",
                    "reorder_authentication::log",
                    "delete_authentication::log",
                    "delete_any_authentication::log",
                    "force_delete_authentication::log",
                    "force_delete_any_authentication::log",
                    "view_kartukeluarga",
                    "view_any_kartukeluarga",
                    "create_kartukeluarga",
                    "update_kartukeluarga",
                    "restore_kartukeluarga",
                    "restore_any_kartukeluarga",
                    "replicate_kartukeluarga",
                    "reorder_kartukeluarga",
                    "delete_kartukeluarga",
                    "delete_any_kartukeluarga",
                    "force_delete_kartukeluarga",
                    "force_delete_any_kartukeluarga",
                    "view_penduduk",
                    "view_any_penduduk",
                    "create_penduduk",
                    "update_penduduk",
                    "restore_penduduk",
                    "restore_any_penduduk",
                    "replicate_penduduk",
                    "reorder_penduduk",
                    "delete_penduduk",
                    "delete_any_penduduk",
                    "force_delete_penduduk",
                    "force_delete_any_penduduk",
                    "view_r::w",
                    "view_any_r::w",
                    "create_r::w",
                    "update_r::w",
                    "restore_r::w",
                    "restore_any_r::w",
                    "replicate_r::w",
                    "reorder_r::w",
                    "delete_r::w",
                    "delete_any_r::w",
                    "force_delete_r::w",
                    "force_delete_any_r::w",
                    "view_shield::role",
                    "view_any_shield::role",
                    "create_shield::role",
                    "update_shield::role",
                    "delete_shield::role",
                    "delete_any_shield::role",
                    "view_user",
                    "view_any_user",
                    "create_user",
                    "update_user",
                    "restore_user",
                    "restore_any_user",
                    "replicate_user",
                    "reorder_user",
                    "delete_user",
                    "delete_any_user",
                    "force_delete_user",
                    "force_delete_any_user",
                    "widget_StatsOverview",
                    "page_MyProfilePage",
                    "view_berita",
                    "view_any_berita",
                    "create_berita",
                    "update_berita",
                    "restore_berita",
                    "restore_any_berita",
                    "replicate_berita",
                    "reorder_berita",
                    "delete_berita",
                    "delete_any_berita",
                    "force_delete_berita",
                    "force_delete_any_berita",
                    "view_kategori::berita",
                    "view_any_kategori::berita",
                    "create_kategori::berita",
                    "update_kategori::berita",
                    "restore_kategori::berita",
                    "restore_any_kategori::berita",
                    "replicate_kategori::berita",
                    "reorder_kategori::berita",
                    "delete_kategori::berita",
                    "delete_any_kategori::berita",
                    "force_delete_kategori::berita",
                    "force_delete_any_kategori::berita",
                    "view_r::t",
                    "view_any_r::t",
                    "create_r::t",
                    "update_r::t",
                    "restore_r::t",
                    "restore_any_r::t",
                    "replicate_r::t",
                    "reorder_r::t",
                    "delete_r::t",
                    "delete_any_r::t",
                    "force_delete_r::t",
                    "force_delete_any_r::t",
                    "view_statistik",
                    "view_any_statistik",
                    "create_statistik",
                    "update_statistik",
                    "restore_statistik",
                    "restore_any_statistik",
                    "replicate_statistik",
                    "reorder_statistik",
                    "delete_statistik",
                    "delete_any_statistik",
                    "force_delete_statistik",
                    "force_delete_any_statistik",
                    "view_wilayah",
                    "view_any_wilayah",
                    "create_wilayah",
                    "update_wilayah",
                    "restore_wilayah",
                    "restore_any_wilayah",
                    "replicate_wilayah",
                    "reorder_wilayah",
                    "delete_wilayah",
                    "delete_any_wilayah",
                    "force_delete_wilayah",
                    "force_delete_any_wilayah",
                    "page_Generator",
                    "page_PendudukStats"
                    ]
            },
            {
                "name": "RT",
                "guard_name": "web",
                "permissions": [
                    "view_kartukeluarga",
                    "view_any_kartukeluarga",
                    "create_kartukeluarga",
                    "update_kartukeluarga",
                    "restore_kartukeluarga",
                    "restore_any_kartukeluarga",
                    "replicate_kartukeluarga",
                    "reorder_kartukeluarga",
                    "delete_kartukeluarga",
                    "delete_any_kartukeluarga",
                    "force_delete_kartukeluarga",
                    "force_delete_any_kartukeluarga",
                    "view_penduduk",
                    "view_any_penduduk",
                    "create_penduduk",
                    "update_penduduk",
                    "restore_penduduk",
                    "restore_any_penduduk",
                    "replicate_penduduk",
                    "reorder_penduduk",
                    "delete_penduduk",
                    "delete_any_penduduk",
                    "force_delete_penduduk",
                    "force_delete_any_penduduk",
                    "view_r::w",
                    "view_any_r::w",
                    "create_r::w",
                    "update_r::w",
                    "restore_r::w",
                    "restore_any_r::w",
                    "replicate_r::w",
                    "reorder_r::w",
                    "delete_r::w",
                    "delete_any_r::w",
                    "force_delete_r::w",
                    "force_delete_any_r::w",
                    "view_rt",
                    "view_any_rt",
                    "create_rt",
                    "update_rt",
                    "restore_rt",
                    "restore_any_rt",
                    "replicate_rt",
                    "reorder_rt",
                    "delete_rt",
                    "delete_any_rt",
                    "force_delete_rt",
                    "force_delete_any_rt",
                    "view_s::l::s",
                    "view_any_s::l::s",
                    "create_s::l::s",
                    "update_s::l::s",
                    "restore_s::l::s",
                    "restore_any_s::l::s",
                    "replicate_s::l::s",
                    "reorder_s::l::s",
                    "delete_s::l::s",
                    "delete_any_s::l::s",
                    "force_delete_s::l::s",
                    "force_delete_any_s::l::s",
                    "widget_StatsOverview",
                    "page_MyProfilePage"
                ]
            }
            ]';
        $directPermissions = '
            { 
            "90": {
                "name": "widget_PendudukApexBarChart",
                "guard_name": "web"
                },
            "91": {
                "name": "widget_PendudukChart",
                "guard_name": "web"
                },
            }';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (!blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (!blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (!blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}