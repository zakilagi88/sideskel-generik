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

        $rolesWithPermissions = '[{"name":"Admin","guard_name":"web","permissions":["view_aparatur","view_any_aparatur","create_aparatur","update_aparatur","delete_aparatur","delete_any_aparatur","restore_aparatur","restore_any_aparatur","view_bantuan","view_any_bantuan","create_bantuan","update_bantuan","delete_bantuan","delete_any_bantuan","restore_bantuan","restore_any_bantuan","view_berita","view_any_berita","create_berita","update_berita","delete_berita","delete_any_berita","reorder_berita","replicate_berita","view_deskel::profile","view_any_deskel::profile","create_deskel::profile","update_deskel::profile","restore_deskel::profile","restore_any_deskel::profile","replicate_deskel::profile","reorder_deskel::profile","delete_deskel::profile","delete_any_deskel::profile","force_delete_deskel::profile","force_delete_any_deskel::profile","view_dinamika","view_any_dinamika","create_dinamika","update_dinamika","delete_dinamika","delete_any_dinamika","restore_dinamika","restore_any_dinamika","view_jabatan","view_any_jabatan","create_jabatan","update_jabatan","restore_jabatan","restore_any_jabatan","replicate_jabatan","reorder_jabatan","delete_jabatan","delete_any_jabatan","force_delete_jabatan","force_delete_any_jabatan","view_jadwal::kegiatan","view_any_jadwal::kegiatan","create_jadwal::kegiatan","update_jadwal::kegiatan","restore_jadwal::kegiatan","restore_any_jadwal::kegiatan","replicate_jadwal::kegiatan","reorder_jadwal::kegiatan","delete_jadwal::kegiatan","delete_any_jadwal::kegiatan","force_delete_jadwal::kegiatan","force_delete_any_jadwal::kegiatan","view_kartu::keluarga","view_any_kartu::keluarga","create_kartu::keluarga","update_kartu::keluarga","delete_kartu::keluarga","delete_any_kartu::keluarga","restore_kartu::keluarga","restore_any_kartu::keluarga","force_delete_kartu::keluarga","force_delete_any_kartu::keluarga","replicate_kartu::keluarga","reorder_kartu::keluarga","view_kategori::berita","view_any_kategori::berita","create_kategori::berita","update_kategori::berita","delete_kategori::berita","delete_any_kategori::berita","replicate_kategori::berita","reorder_kategori::berita","view_keamanan::dan::lingkungan","view_any_keamanan::dan::lingkungan","create_keamanan::dan::lingkungan","update_keamanan::dan::lingkungan","restore_keamanan::dan::lingkungan","restore_any_keamanan::dan::lingkungan","replicate_keamanan::dan::lingkungan","reorder_keamanan::dan::lingkungan","delete_keamanan::dan::lingkungan","delete_any_keamanan::dan::lingkungan","force_delete_keamanan::dan::lingkungan","force_delete_any_keamanan::dan::lingkungan","view_keputusan","view_any_keputusan","create_keputusan","update_keputusan","delete_keputusan","delete_any_keputusan","restore_keputusan","restore_any_keputusan","view_kesehatan::anak","view_any_kesehatan::anak","create_kesehatan::anak","update_kesehatan::anak","delete_kesehatan::anak","delete_any_kesehatan::anak","view_lembaga","view_any_lembaga","create_lembaga","update_lembaga","restore_lembaga","restore_any_lembaga","replicate_lembaga","reorder_lembaga","delete_lembaga","delete_any_lembaga","force_delete_lembaga","force_delete_any_lembaga","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","delete_penduduk","delete_any_penduduk","restore_penduduk","restore_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","replicate_penduduk","reorder_penduduk","view_peraturan","view_any_peraturan","create_peraturan","update_peraturan","delete_peraturan","delete_any_peraturan","restore_peraturan","restore_any_peraturan","view_potensi::s::d::a","view_any_potensi::s::d::a","create_potensi::s::d::a","update_potensi::s::d::a","restore_potensi::s::d::a","restore_any_potensi::s::d::a","replicate_potensi::s::d::a","reorder_potensi::s::d::a","delete_potensi::s::d::a","delete_any_potensi::s::d::a","force_delete_potensi::s::d::a","force_delete_any_potensi::s::d::a","view_sarana::prasarana","view_any_sarana::prasarana","create_sarana::prasarana","update_sarana::prasarana","restore_sarana::prasarana","restore_any_sarana::prasarana","replicate_sarana::prasarana","reorder_sarana::prasarana","delete_sarana::prasarana","delete_any_sarana::prasarana","force_delete_sarana::prasarana","force_delete_any_sarana::prasarana","view_shield::role","view_any_shield::role","create_shield::role","update_shield::role","delete_shield::role","delete_any_shield::role","view_shield::user","view_any_shield::user","create_shield::user","update_shield::user","delete_shield::user","delete_any_shield::user","view_stat::s::d::m","view_any_stat::s::d::m","create_stat::s::d::m","update_stat::s::d::m","delete_stat::s::d::m","delete_any_stat::s::d::m","view_tambahan","view_any_tambahan","create_tambahan","update_tambahan","delete_tambahan","delete_any_tambahan","restore_tambahan","restore_any_tambahan","view_wilayah","view_any_wilayah","create_wilayah","update_wilayah","restore_wilayah","restore_any_wilayah","replicate_wilayah","reorder_wilayah","delete_wilayah","delete_any_wilayah","force_delete_wilayah","force_delete_any_wilayah","page_PengaturanUmum","page_HalamanArsip","page_HalamanBerita","page_HalamanDesa","page_HalamanKependudukan","page_HalamanKesehatan","page_HalamanPotensi","page_HalamanStatistik","page_HalamanWilayah"]},{"name":"Admin Web","guard_name":"web","permissions":["view_aparatur","view_any_aparatur","create_aparatur","update_aparatur","delete_aparatur","delete_any_aparatur","restore_aparatur","restore_any_aparatur","view_bantuan","view_any_bantuan","create_bantuan","update_bantuan","view_berita","view_any_berita","create_berita","update_berita","delete_berita","delete_any_berita","reorder_berita","replicate_berita","view_deskel::profile","view_any_deskel::profile","create_deskel::profile","update_deskel::profile","restore_deskel::profile","restore_any_deskel::profile","replicate_deskel::profile","reorder_deskel::profile","delete_deskel::profile","delete_any_deskel::profile","force_delete_deskel::profile","force_delete_any_deskel::profile","view_jabatan","view_any_jabatan","create_jabatan","update_jabatan","restore_jabatan","restore_any_jabatan","replicate_jabatan","reorder_jabatan","delete_jabatan","delete_any_jabatan","force_delete_jabatan","force_delete_any_jabatan","view_jadwal::kegiatan","view_any_jadwal::kegiatan","create_jadwal::kegiatan","update_jadwal::kegiatan","restore_jadwal::kegiatan","restore_any_jadwal::kegiatan","replicate_jadwal::kegiatan","reorder_jadwal::kegiatan","delete_jadwal::kegiatan","delete_any_jadwal::kegiatan","force_delete_jadwal::kegiatan","force_delete_any_jadwal::kegiatan","view_keamanan::dan::lingkungan","view_any_keamanan::dan::lingkungan","create_keamanan::dan::lingkungan","update_keamanan::dan::lingkungan","restore_keamanan::dan::lingkungan","restore_any_keamanan::dan::lingkungan","replicate_keamanan::dan::lingkungan","reorder_keamanan::dan::lingkungan","delete_keamanan::dan::lingkungan","delete_any_keamanan::dan::lingkungan","force_delete_keamanan::dan::lingkungan","force_delete_any_keamanan::dan::lingkungan","view_keputusan","view_any_keputusan","create_keputusan","update_keputusan","delete_keputusan","delete_any_keputusan","restore_keputusan","restore_any_keputusan","view_lembaga","view_any_lembaga","create_lembaga","update_lembaga","restore_lembaga","restore_any_lembaga","replicate_lembaga","reorder_lembaga","delete_lembaga","delete_any_lembaga","force_delete_lembaga","force_delete_any_lembaga","view_peraturan","view_any_peraturan","create_peraturan","update_peraturan","delete_peraturan","delete_any_peraturan","restore_peraturan","restore_any_peraturan","view_potensi::s::d::a","view_any_potensi::s::d::a","create_potensi::s::d::a","update_potensi::s::d::a","restore_potensi::s::d::a","restore_any_potensi::s::d::a","replicate_potensi::s::d::a","reorder_potensi::s::d::a","delete_potensi::s::d::a","delete_any_potensi::s::d::a","force_delete_potensi::s::d::a","force_delete_any_potensi::s::d::a","view_sarana::prasarana","view_any_sarana::prasarana","create_sarana::prasarana","update_sarana::prasarana","restore_sarana::prasarana","restore_any_sarana::prasarana","replicate_sarana::prasarana","reorder_sarana::prasarana","delete_sarana::prasarana","delete_any_sarana::prasarana","force_delete_sarana::prasarana","force_delete_any_sarana::prasarana","view_stat::s::d::m","view_any_stat::s::d::m","create_stat::s::d::m","update_stat::s::d::m","delete_stat::s::d::m","delete_any_stat::s::d::m","view_tambahan","view_any_tambahan","create_tambahan","update_tambahan","page_PengaturanUmum","page_HalamanArsip","page_HalamanBerita","page_HalamanDesa","page_HalamanKependudukan","page_HalamanPotensi","page_HalamanStatistik"]},{"name":"Monitor Wilayah","guard_name":"web","permissions":["view_bantuan","view_any_bantuan","update_bantuan","view_dinamika","view_any_dinamika","view_jadwal::kegiatan","view_any_jadwal::kegiatan","view_kartu::keluarga","view_any_kartu::keluarga","view_kesehatan::anak","view_any_kesehatan::anak","view_penduduk","view_any_penduduk","view_stat::s::d::m","view_any_stat::s::d::m","view_tambahan","view_any_tambahan","update_tambahan","page_HalamanDesa","page_HalamanKependudukan","page_HalamanKesehatan","page_HalamanStatistik"]},{"name":"Operator Wilayah","guard_name":"web","permissions":["view_bantuan","view_any_bantuan","update_bantuan","view_dinamika","view_any_dinamika","create_dinamika","update_dinamika","delete_dinamika","delete_any_dinamika","restore_dinamika","restore_any_dinamika","view_jadwal::kegiatan","view_any_jadwal::kegiatan","view_kartu::keluarga","view_any_kartu::keluarga","create_kartu::keluarga","update_kartu::keluarga","delete_kartu::keluarga","delete_any_kartu::keluarga","restore_kartu::keluarga","restore_any_kartu::keluarga","force_delete_kartu::keluarga","force_delete_any_kartu::keluarga","replicate_kartu::keluarga","reorder_kartu::keluarga","view_kesehatan::anak","view_any_kesehatan::anak","create_kesehatan::anak","update_kesehatan::anak","delete_kesehatan::anak","delete_any_kesehatan::anak","view_penduduk","view_any_penduduk","create_penduduk","update_penduduk","delete_penduduk","delete_any_penduduk","restore_penduduk","restore_any_penduduk","force_delete_penduduk","force_delete_any_penduduk","replicate_penduduk","reorder_penduduk","view_stat::s::d::m","view_any_stat::s::d::m","view_tambahan","view_any_tambahan","update_tambahan","page_HalamanDesa","page_HalamanKependudukan","page_HalamanKesehatan","page_HalamanStatistik"]}]';
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