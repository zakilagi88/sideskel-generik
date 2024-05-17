<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $menus =
            [
                [
                    "name" => "Beranda",
                    "submenu" => [],
                    "link_name" => "index.beranda",
                    "link_type" => "static",
                ],
                [
                    "name" => "Profil ",
                    "submenu" => [
                        [
                            "sub_name" => "Profil Desa/Kelurahan",
                            "sub_link_name" => "index.profil.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "1",
                        ],
                        [
                            "sub_name" => "Aparatur",
                            "sub_link_name" => "index.aparatur",
                            "sub_link_type" => "static",
                            "sub_link_options" => null,
                        ],
                        [
                            "sub_name" => "Lembaga",
                            "sub_link_name" => "index.lembaga",
                            "sub_link_type" => "static",
                            "sub_link_options" => null,
                        ],
                    ],
                    "link_name" => "index.beranda",
                    "link_type" => "static",
                ],
                [
                    "name" => "Lembaga",
                    "submenu" => [
                        [
                            "sub_name" => "Badan Permusyawaratan Desa",
                            "sub_link_name" => "index.lembaga.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "badan-permusyawaratan-desa",
                        ],
                        [
                            "sub_name" => "Karang Taruna",
                            "sub_link_name" => "index.lembaga.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "karang-taruna",
                        ],
                    ],
                    "link_name" => "index.lembaga",
                    "link_type" => "static",
                ],
                [
                    "name" => "Berita",
                    "submenu" => [],
                    "link_name" => "index.berita",
                    "link_type" => "static",
                ],
                [
                    "name" => "Stat",
                    "submenu" => [
                        [
                            "sub_name" => "Agama",
                            "sub_link_name" => "index.stat.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "agama",
                        ],
                        [
                            "sub_name" => "Pendidikan",
                            "sub_link_name" => "index.stat.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "pendidikan",
                        ],
                        [
                            "sub_name" => "Pekerjaan",
                            "sub_link_name" => "index.stat.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "pekerjaan",
                        ],
                        [
                            "sub_name" => "Kategori Umur",
                            "sub_link_name" => "index.stat.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "kategori-umur",
                        ],
                        [
                            "sub_name" => "Hubungan Keluarga",
                            "sub_link_name" => "index.stat.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "status-hubungan-keluarga",
                        ],
                    ],
                    "link_name" => "index.beranda",
                    "link_type" => "static",
                ],
                [
                    "name" => "Potensi",
                    "submenu" => [
                        [
                            "sub_name" => "Pertanian Dan Perkebunan",
                            "sub_link_name" => "index.potensi.sda.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "pertanian-perkebunan",
                        ],
                        [
                            "sub_name" => "Kehutanan",
                            "sub_link_name" => "index.potensi.sda.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "kehutanan",
                        ],
                        [
                            "sub_name" => "Peternakan",
                            "sub_link_name" => "index.potensi.sda.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "peternakan",
                        ],
                        [
                            "sub_name" => "Perikanan",
                            "sub_link_name" => "index.potensi.sda.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "perikanan",
                        ],
                        [
                            "sub_name" => "Bahan Galian",
                            "sub_link_name" => "index.potensi.sda.show",
                            "sub_link_type" => "dynamic",
                            "sub_link_options" => "bahan-galian",
                        ],
                    ],
                    "link_name" => "index.potensi",
                    "link_type" => "static",
                ],
                [
                    "name" => "Arsip",
                    "submenu" => [
                        [
                            "sub_name" => "Peraturan",
                            "sub_link_name" => "index.peraturan",
                            "sub_link_type" => "static",
                            "sub_link_options" => null,
                        ],
                        [
                            "sub_name" => "Keputusan",
                            "sub_link_name" => "index.keputusan",
                            "sub_link_type" => "static",
                            "sub_link_options" => null,
                        ],
                    ],
                    "link_name" => "index.peraturan",
                    "link_type" => "static",
                ],
            ];


        $this->migrator->add('web.menus', $menus);
        $this->migrator->add('web.web_active', false);
        $this->migrator->add('web.web_title', 'Website Resmi');
        $this->migrator->add('web.web_gambar', 'sites/logo.png');
        $this->migrator->add('web.kepala_gambar', 'deskel\aparatur\gambar-aparatur-man.png');
        $this->migrator->add('web.kepala_judul', 'Sambutan Kepala Desa/Kelurahan');
        $this->migrator->add('web.kepala_nama', 'Nama Kepala Desa/Kelurahan');
        $this->migrator->add('web.kepala_deskripsi', 'Deskripsi Kepala Desa/Kelurahan');
        $this->migrator->add('web.berita_judul', 'Berita Terbaru');
        $this->migrator->add('web.berita_deskripsi', 'Berita ini akan selalu di update setiap ada berita terbaru.');
        $this->migrator->add(
            'web.footer_deskripsi',
            'Website Resmi Kelurahan Anda adalah website resmi yang dikelola oleh Pemerintah Kelurahan Anda. Website ini bertujuan untuk memberikan informasi publik kepada masyarakat Kelurahan Anda.'
        );
    }
};
