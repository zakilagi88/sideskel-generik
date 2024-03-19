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

        $rolesWithPermissions = '[{"name":"Admin","guard_name":"web","permissions":["view_bantuan","view_any_bantuan","create_bantuan","update_bantuan","delete_bantuan","delete_any_bantuan","restore_bantuan","restore_any_bantuan","view_berita","view_any_berita","create_berita","update_berita","delete_berita","delete_any_berita","reorder_berita","replicate_berita","view_dinamika","view_any_dinamika","create_dinamika","update_dinamika","delete_dinamika","delete_any_dinamika","restore_dinamika","restore_any_dinamika","view_kartu::keluarga","view_any_kartu::keluarga","create_kartu::keluarga","update_kartu::keluarga","delete_kartu::keluarga","delete_any_kartu::keluarga","restore_kartu::keluarga","restore_any_kartu::keluarga","force_delete_kartu::keluarga","force_delete_any_kartu::keluarga","replicate_kartu::keluarga","reorder_kartu::keluarga","view_kategori::berita","view_any_kategori::berita","create_kategori::berita","update_kategori::berita","delete_kategori::berita","delete_any_kategori::berita","replicate_kategori::berita","reorder_kategori::berita","view_kesehatan::anak","view_any_kesehatan::anak","create_kesehatan::anak","update_kesehatan::anak","delete_kesehatan::anak","delete_any_kesehatan::anak","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","delete_penduduk","delete_any_penduduk","restore_penduduk","restore_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","replicate_penduduk","reorder_penduduk","view_r::t","view_any_r::t","create_r::t","update_r::t","restore_r::t","restore_any_r::t","replicate_r::t","reorder_r::t","delete_r::t","delete_any_r::t","force_delete_r::t","force_delete_any_r::t","view_r::w","view_any_r::w","create_r::w","update_r::w","restore_r::w","restore_any_r::w","replicate_r::w","reorder_r::w","delete_r::w","delete_any_r::w","force_delete_r::w","force_delete_any_r::w","view_shield::autentikasi::log","view_any_shield::autentikasi::log","create_shield::autentikasi::log","update_shield::autentikasi::log","delete_shield::autentikasi::log","delete_any_shield::autentikasi::log","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_shield::user","view_any_shield::user","create_shield::user","update_shield::user","delete_shield::user","delete_any_shield::user","view_web::statistik","view_any_web::statistik","create_web::statistik","update_web::statistik","delete_web::statistik","delete_any_web::statistik","view_wilayah","view_any_wilayah","create_wilayah","update_wilayah","restore_wilayah","restore_any_wilayah","replicate_wilayah","reorder_wilayah","delete_wilayah","delete_any_wilayah","force_delete_wilayah","force_delete_any_wilayah","page_DeskelProfile","page_HalamanBerita","page_HalamanKependudukan","page_HalamanKesehatan","page_PendudukAgama","page_PendudukKategoriUmur","page_PendudukPekerjaan","page_PendudukPendidikan","page_PendudukPerkawinan","page_PendudukRentangUmur","page_PendudukUmur","page_HalamanStatistik","page_WilayahAdministratif","page_HalamanWilayah","page_MyProfilePage","widget_AgamaChart","widget_KategoriUmurChart","widget_PekerjaanChart","widget_PendidikanChart","widget_PerkawinanChart","widget_RentangUmurChart","widget_UmurChart","widget_StatsOverview","widget_DinamikaTable","widget_AgamaTable","widget_KategoriUmurTable","widget_PekerjaanTable","widget_PendidikanTable","widget_PerkawinanTable","widget_RentangUmurTable","widget_UmurTable","widget_SistemPreparation","view_aparatur","view_any_aparatur","create_aparatur","update_aparatur","delete_aparatur","delete_any_aparatur","restore_aparatur","restore_any_aparatur","view_keputusan","view_any_keputusan","create_keputusan","update_keputusan","delete_keputusan","delete_any_keputusan","restore_keputusan","restore_any_keputusan","view_peraturan","view_any_peraturan","create_peraturan","update_peraturan","delete_peraturan","delete_any_peraturan","restore_peraturan","restore_any_peraturan","view_tambahan","view_any_tambahan","create_tambahan","update_tambahan","delete_tambahan","delete_any_tambahan","restore_tambahan","restore_any_tambahan","page_HalamanDesa","widget_JadwalKegiatanWidget"]},{"name":"Admin Desa","guard_name":"web","permissions":["view_berita","view_any_berita","create_berita","update_berita","delete_berita","delete_any_berita","reorder_berita","replicate_berita","view_kategori::berita","view_any_kategori::berita","create_kategori::berita","update_kategori::berita","delete_kategori::berita","delete_any_kategori::berita","replicate_kategori::berita","reorder_kategori::berita","view_web::statistik","view_any_web::statistik","create_web::statistik","update_web::statistik","delete_web::statistik","delete_any_web::statistik","page_DeskelProfile","page_HalamanBerita","page_HalamanKesehatan","page_PendudukAgama","page_PendudukKategoriUmur","page_PendudukPekerjaan","page_PendudukPendidikan","page_PendudukPerkawinan","page_PendudukRentangUmur","page_PendudukUmur","page_HalamanStatistik","page_MyProfilePage","widget_AgamaChart","widget_KategoriUmurChart","widget_PekerjaanChart","widget_PendidikanChart","widget_PerkawinanChart","widget_RentangUmurChart","widget_UmurChart","widget_StatsOverview","widget_DinamikaTable","widget_AgamaTable","widget_KategoriUmurTable","widget_PekerjaanTable","widget_PendidikanTable","widget_PerkawinanTable","widget_RentangUmurTable","widget_UmurTable","view_aparatur","view_any_aparatur","create_aparatur","update_aparatur","delete_aparatur","delete_any_aparatur","restore_aparatur","restore_any_aparatur","view_keputusan","view_any_keputusan","create_keputusan","update_keputusan","delete_keputusan","delete_any_keputusan","restore_keputusan","restore_any_keputusan","view_peraturan","view_any_peraturan","create_peraturan","update_peraturan","delete_peraturan","delete_any_peraturan","restore_peraturan","restore_any_peraturan","page_HalamanDesa","widget_JadwalKegiatanWidget"]},{"name":"Operator Wilayah","guard_name":"web","permissions":["view_bantuan","view_any_bantuan","create_bantuan","update_bantuan","delete_bantuan","delete_any_bantuan","restore_bantuan","restore_any_bantuan","view_dinamika","view_any_dinamika","create_dinamika","update_dinamika","delete_dinamika","delete_any_dinamika","restore_dinamika","restore_any_dinamika","view_kartu::keluarga","view_any_kartu::keluarga","create_kartu::keluarga","update_kartu::keluarga","delete_kartu::keluarga","delete_any_kartu::keluarga","restore_kartu::keluarga","restore_any_kartu::keluarga","force_delete_kartu::keluarga","force_delete_any_kartu::keluarga","replicate_kartu::keluarga","reorder_kartu::keluarga","view_kesehatan::anak","view_any_kesehatan::anak","create_kesehatan::anak","update_kesehatan::anak","delete_kesehatan::anak","delete_any_kesehatan::anak","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","delete_penduduk","delete_any_penduduk","restore_penduduk","restore_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","replicate_penduduk","reorder_penduduk","page_HalamanKependudukan","page_HalamanKesehatan","page_PendudukAgama","page_PendudukKategoriUmur","page_PendudukPekerjaan","page_PendudukPendidikan","page_PendudukPerkawinan","page_PendudukRentangUmur","page_PendudukUmur","page_HalamanStatistik","page_MyProfilePage","widget_AgamaChart","widget_KategoriUmurChart","widget_PekerjaanChart","widget_PendidikanChart","widget_PerkawinanChart","widget_RentangUmurChart","widget_UmurChart","widget_StatsOverview","widget_DinamikaTable","widget_AgamaTable","widget_KategoriUmurTable","widget_PekerjaanTable","widget_PendidikanTable","widget_PerkawinanTable","widget_RentangUmurTable","widget_UmurTable","view_tambahan","view_any_tambahan","update_tambahan","widget_JadwalKegiatanWidget"]},{"name":"Monitor Wilayah","guard_name":"web","permissions":["view_bantuan","view_any_bantuan","view_dinamika","view_any_dinamika","view_kartu::keluarga","view_any_kartu::keluarga","view_kesehatan::anak","view_any_kesehatan::anak","view_penduduk","view_any_penduduk","page_HalamanKependudukan","page_HalamanKesehatan","page_PendudukAgama","page_PendudukKategoriUmur","page_PendudukPekerjaan","page_PendudukPendidikan","page_PendudukPerkawinan","page_PendudukRentangUmur","page_PendudukUmur","page_HalamanStatistik","page_MyProfilePage","widget_AgamaChart","widget_KategoriUmurChart","widget_PekerjaanChart","widget_PendidikanChart","widget_PerkawinanChart","widget_RentangUmurChart","widget_UmurChart","widget_StatsOverview","widget_DinamikaTable","widget_AgamaTable","widget_KategoriUmurTable","widget_PekerjaanTable","widget_PendidikanTable","widget_PerkawinanTable","widget_RentangUmurTable","widget_UmurTable","view_tambahan","view_any_tambahan","widget_JadwalKegiatanWidget"]}]';
        $directPermissions = '[]';

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
