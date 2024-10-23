<?php

return [
    'general_settings' => [
        'title' => 'Pengaturan Umum',
        'heading' => 'Pengaturan Umum',
        'subheading' => 'Kelola pengaturan situs umum di sini.',
        'navigationLabel' => 'Umum',
        'sections' => [
            'site' => [
                'title' => 'Lokasi',
                'description' => 'Kelola pengaturan dasar.',
            ],
            'theme' => [
                'title' => 'Tema',
                'description' => 'Ubah tema default.',
            ],
        ],
        'fields' => [
            'brand_name' => 'Nama merk',
            'brand_name_hint' => 'Judul Tab Browser pada Panel Admin',
            'site_active' => 'Status Situs',
            'brand_logoHeight' => 'Tinggi Logo Merek',
            'brand_logo' => 'Logo Merek',
            'site_favicon' => 'Situs Favicon',
            'primary' => 'Utama',
            'secondary' => 'Sekunder',
            'gray' => 'Abu-abu',
            'success' => 'Kesuksesan',
            'danger' => 'Bahaya',
            'info' => 'Informasi',
            'warning' => 'Peringatan',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Halaman Potensi
    |--------------------------------------------------------------------------
    */
    'potensi-pages' => [
        'title' => 'Halaman Potensi',
        'heading' => 'Halaman Potensi',
        'subheading' => 'Kelola halaman potensi di sini.',
        'navigationLabel' => 'Potensi',
        'actions' => [
            'save' => [
                'label' => 'Simpan',
            ],
        ],
        'fields' => [
            'pertanian' => [
                0 => [
                    'label' => 'Luas Tanaman Pangan Menurut Komoditas',
                    'atribut' => [
                        0 => 'Nama Komoditas',
                        1 => 'Luas (Ha)',
                        2 => 'Hasil Panen (Ton/Ha)',

                    ],
                ],
            ],
            'perkebunan' => [
                0 => [
                    'label' => 'Jenis komoditas buah-buahan yang dibudidayakan',
                    'atribut' => [
                        0 => 'Nama Komoditas',
                        1 => 'Luas (Ha)',
                        2 => 'Hasil Panen (Ton/Ha)',
                    ],
                ],
                1 => [
                    'label' => 'Tanaman Apotik Hidup Dan Sejenisnya',
                    'atribut' => [
                        0 => 'Nama Komoditas',
                        1 => 'Luas (Ha)',
                        2 => 'Hasil Panen (Ton/Ha)',
                    ],
                ],
                2 => [
                    'label' => 'Luas dan hasil perkebunan menurut jenis komoditas',
                    'atribut' => [
                        0 => 'Jenis Komoditas',
                        1 => 'Luas (Ha) - Swasta/Negara',
                        2 => 'Hasil Panen (Ton/Ha) - Swasta/Negara',
                        3 => 'Luas (Ha) - Rakyat',
                        4 => 'Hasil Panen (Ton/Ha) - Rakyat',
                    ],
                ],
            ],
            'kehutanan' => [
                0 => [
                    'label' => 'Hasil Hutan',
                    'atribut' => [
                        0 => 'Nama Komoditas',
                        1 => 'Hasil Panen',
                        2 => 'Satuan',
                    ],
                ],
                1 => [
                    'label' => 'Kondisi Hutan',
                    'atribut' => [
                        0 => 'Jenis Hutan',
                        1 => 'Kondisi Baik (Ha)',
                        2 => 'Kondisi Rusak (Ha)',
                        3 => 'Total (Ha)',
                    ],
                ],
                2 => [
                    'label' => 'Dampak yang Timbul dari Pengolahan Hutan',
                    'atribut' => [
                        0 => 'Jenis Dampak',
                        1 => 'Dampak',
                    ],
                ],
            ],
            'peternakan' => [
                0 => [
                    'label' => 'Jumlah Populasi Ternak Menurut Jenis Ternak',
                    'atribut' => [
                        0 => 'Jenis Ternak',
                        1 => 'Jumlah Populasi',
                        2 => 'Perkiraan Jumlah Populasi (Ekor)',
                    ],
                ],
                1 => [
                    'label' => 'Produksi Peternakan',
                    'atribut' => [
                        0 => 'Jenis Produksi',
                        1 => 'Hasil Produksi',
                        2 => 'Satuan',
                    ],
                ],
                2 => [
                    'label' => 'Ketersediaan Hijauan Pakan Ternak',
                    'atribut' => [
                        0 => 'Keterangan',
                        1 => 'Jumlah',
                        2 => 'Satuan',

                    ],
                ],
                3 => [
                    'label' => 'Pemilik Usaha Pengolahan Hasil Ternak',
                    'atribut' => [
                        0 => 'Jenis Usaha',
                        1 => 'Jumlah Pemilik Usaha',
                    ],
                ],
                4 => [
                    'label' => 'Ketersediaan lahan pemeliharaan ternak/padang penggembalaan',
                    'atribut' => [
                        0 => 'Jenis Kepemilikan Lahan',
                        1 => 'Luas',
                    ],
                ],
            ],
            'perikanan' => [
                0 => [
                    'label' => 'Jenis dan Alat Produksi Budidaya Ikan Laut dan Payau',
                    'atribut' => [
                        0 => 'Jenis Alat',
                        1 => 'Jumlah',
                        2 => 'Satuan',
                        3 => 'Hasil Produksi (Ton/Tahun)',
                    ],
                ],
                1 => [
                    'label' => 'Jenis dan Sarana Produksi Budidaya Ikan Air Tawar',
                    'atribut' => [
                        0 => 'Jenis Sarana',
                        1 => 'Jumlah',
                        2 => 'Satuan',
                        3 => 'Hasil Produksi (Ton/Tahun)',
                    ],
                ],
                2 => [
                    'label' => 'Jenis Ikan dan Produksi',
                    'atribut' => [
                        0 => 'Jenis Ikan',
                        1 => 'Hasil Produksi (Ton/Tahun)',
                    ],
                ],
            ],
            'bahan-galian' => [
                0 => [
                    'label' => 'Jenis, deposit dan kepemilikan bahan galian',
                    'atribut' => [
                        0 => 'Jenis Bahan Galian',
                        1 => 'Keberadaan',
                        2 => 'Skala Produksi',
                        3 => 'Kepemilikan',
                    ],
                ],
            ],
            'sumber-daya-air' => [
                0 => [
                    'label' => 'Potensi Air dan Sumber Daya Air',
                    'atribut' => [
                        0 => 'Jenis Sumber Air',
                        1 => 'Debit Volume',
                    ],
                ],
                1 => [
                    'label' => 'Sumber dan Kualitas Air Bersih',
                    'atribut' => [
                        0 => 'Jenis',
                        1 => 'Jumlah Unit',
                        2 => 'Kondisi Rusak',
                        3 => 'Pemanfaatan (KK)',
                        4 => 'Kualitas',
                    ],
                ],
                2 => [
                    'label' => 'Sungai',
                    'atribut' => [
                        0 => 'Kondisi',
                        1 => 'Keterangan (Ya/Tidak)',
                    ],
                ],
                3 => [
                    'label' => 'Rawa',
                    'atribut' => [
                        0 => 'Pemanfaatan',
                        1 => 'Keterangan (Ya/Tidak)',
                    ],
                ],
                4 => [
                    'label' => 'Pemanfaatan dan kondisi danau/waduk/situ',
                    'atribut' => [
                        0 => 'Pemanfaatan',
                        1 => 'Keterangan (Ya/Tidak)',
                    ],
                ],
                5 => [
                    'label' => 'Pemanfaatan dan kondisi danau/waduk/situ',
                    'atribut' => [
                        0 => 'Kondisi',
                        1 => 'Keterangan (Ya/Tidak)',
                    ],
                ],
                6 => [
                    'label' => 'Air Panas',
                    'atribut' => [
                        0 => 'Sumber',
                        1 => 'Jumlah Lokasi',
                        2 => 'Pemanfaatan Wisata',
                        3 => 'Kepemilikian/Pengelolaan',
                    ],
                ],
                7 => [
                    'label' => 'Kualitas Udara',
                    'atribut' => [
                        0 => 'Sumber',
                        1 => 'Jumlah Lokasi',
                        2 => 'Polutan',
                        3 => 'Efek terhadap Kesehatan',
                        4 => 'Kepemilikian',
                    ],
                ],
            ],
            'kebisingan' => [
                0 => [
                    'label' => 'Kebisingan',
                    'atribut' => [
                        0 => 'Tingkat Kebisingan',
                        1 => 'Ekses Dampak Kebisingan',
                        2 => 'Sumber Kebisingan',
                        3 => 'Efek terhadap Penduduk',
                    ],
                ],
            ],
            'ruang-publik' => [
                0 => [
                    'label' => 'Ruang Publik/Taman',
                    'atribut' => [
                        0 => 'Ruang Publik/Taman',
                        1 => 'Keberadaan',
                        2 => 'Luas',
                        3 => 'Tingkat Pemanfaatan',
                    ],
                ],
            ],
            'wisata' => [
                0 => [
                    'label' => 'Jenis dan Jumlah Wisata',
                    'atribut' => [
                        0 => 'Lokasi Tempat/Area Wisata',
                        1 => 'Luas',
                        2 => 'Tingkat Pemanfaatan',
                    ],
                ],
            ]
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Pengaturan Surat
    |--------------------------------------------------------------------------
    */

    'mail_settings' => [
        'title' => 'Pengaturan Surat',
        'heading' => 'Pengaturan Surat',
        'subheading' => 'Kelola konfigurasi email.',
        'navigationLabel' => 'Surat',
        'sections' => [
            'config' => [
                'title' => 'Konfigurasi',
                'description' => 'keterangan',
            ],
            'sender' => [
                'title' => 'Dari (Pengirim)',
                'description' => 'keterangan',
            ],
            'mail_to' => [
                'title' => 'Kirim ke',
                'description' => 'keterangan',
            ],
        ],
        'fields' => [
            'placeholder' => [
                'receiver_email' => 'Email penerima..',
            ],
            'driver' => 'Pengemudi',
            'host' => 'Tuan rumah',
            'port' => 'Pelabuhan',
            'encryption' => 'Enkripsi',
            'timeout' => 'Waktu habis',
            'username' => 'Nama belakang',
            'password' => 'Kata sandi',
            'email' => 'Surel',
            'name' => 'Nama',
            'mail_to' => 'Kirim ke',
        ],
        'actions' => [
            'send_test_mail' => 'Kirim Surat Uji',
        ],
    ]
];