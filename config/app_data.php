<?php

return
    [
        'default_tables' => [
            'stat_kategoris' => [
                1 => ['nama' => 'Statistik Penduduk'],
            ],
            'potensi-sda' => [
                'pertanian-perkebunan' => [
                    [
                        'label' => 'Luas Tanaman Pangan Menurut Komoditas',
                        'entitas' => [[
                            '0 Nama Komoditas' => null,
                            '1 Luas (Ha)' => null,
                            '2 Hasil Panen' => null,
                        ]],
                    ],
                    [
                        'label' => 'Jenis Komoditas Buah Buahan yang dibudidayakan',
                        'entitas' => [[
                            '0 Nama Komoditas' => null,
                            '1 Luas (Ha)' => null,
                            '2 Hasil Panen' => null,
                        ]],
                    ],
                    [
                        'label' => 'Tanaman Apotik Hidup Dan Sejenisnya',
                        'entitas' => [[
                            '0 Nama Tanaman' => null,
                            '1 Luas (Ha)' => null,
                            '2 Hasil Panen' => null,
                        ]],
                    ],

                    [
                        'label' => 'Luas dan hasil perkebunan menurut Jenis Komoditas',
                        'entitas' => [[
                            '0 Jenis Komoditas' => null,
                            '1 Luas (Swasta/Negara)' => null,
                            '2 Hasil Panen (Swasta/Negara)' => null,
                            '3 Luas (Rakyat)' => null,
                            '4 Hasil Panen (Rakyat)' => null,
                        ]],
                    ]

                ],
                'kehutanan' => [
                    [
                        'label' => 'Hasil Hutan',
                        'entitas' => [[
                            '0 Nama Komoditas' => null,
                            '1 Hasil Panen' => null,
                            '2 Satuan' => null,
                        ]],
                    ],
                    [
                        'label' => 'Kondisi Hutan',
                        'entitas' => [[
                            '0 Jenis Hutan' => null,
                            '1 Kondisi Baik' => null,
                            '2 Kondisi Rusak' => null,
                            '3 Total' => null,
                        ]],
                    ],
                    [
                        'label' => 'Dampak yang Timbul dari Pengolahan Hutan',
                        'entitas' => [[
                            '0 Jenis Dampak' => null,
                            '1 Dampak' => null,
                        ]],
                    ],
                ],
                'peternakan' => [
                    [
                        'label' => 'Jumlah Populasi Ternak Menurut Jenis Ternak',
                        'entitas' => [[
                            '0 Jenis Ternak' => null,
                            '1 Jumlah Populasi' => null,
                            '2 Perkiraan Jumlah Populasi' => null,
                        ]],
                    ],
                    [
                        'label' => 'Produksi Peternakan',
                        'entitas' => [[
                            '0 Jenis Produksi' => null,
                            '1 Hasil Produksi' => null,
                            '2 Satuan' => null,

                        ]],
                    ],
                    [
                        'label' => 'Ketersediaan Hijauan Pakan Ternak',
                        'entitas' => [[
                            '0 Keterangan' => null,
                            '1 Jumlah' => null,
                            '2 Satuan' => null,
                        ]],
                    ],
                    [
                        'label' => 'Pemilik Usaha Pengolahan Hasil Ternak',
                        'entitas' => [[
                            '0 Jenis Usaha' => null,
                            '1 Jumlah Pemilik Usaha' => null,
                        ]],
                    ],
                    [
                        'label' => 'Ketersediaan lahan pemeliharaan ternak/padang penggembalaan',
                        'entitas' => [[
                            '0 Jumlah Kepemilikan Lahan' => null,
                            '1 Luas' => null,
                        ]],
                    ]
                ],
                'perikanan' => [
                    [
                        'label' => 'Jenis dan Alat Produksi Budidaya Ikan Laut dan Payau',
                        'entitas' => [[
                            '0 Jenis Alat' => null,
                            '1 Jumlah' => null,
                            '2 Satuan' => null,
                            '3 Hasil Produksi' => null,
                        ]],
                    ],
                    [
                        'label' => 'Jenis dan Sarana Produksi Budidaya Ikan Air Tawar',
                        'entitas' => [[
                            '0 Jenis Sarana' => null,
                            '1 Jumlah' => null,
                            '2 Satuan' => null,
                            '3 Hasil Produksi' => null,
                        ]],
                    ],
                    [
                        'label' => 'Jenis Ikan dan Produksi',
                        'entitas' => [[
                            '0 Jenis Ikan' => null,
                            '1 Hasil Produksi' => null,
                        ]],
                    ],

                ],
                'bahan-galian' => [
                    [
                        'label' => 'Jenis, deposit dan kepemilikan bahan galian',
                        'entitas' => [[
                            '0 Jenis Bahan Galian' => null,
                            '1 Keberadaan' => null,
                            '2 Skala Produksi' => null,
                            '3 Kepemilikan' => null,
                        ]],
                    ],
                ],
                'sumber-daya-air' => [
                    [
                        'label' => 'Potensi Air dan Sumber Daya Air',
                        'entitas' => [[
                            '0 Jenis Sumber Air' => null,
                            '1 Debit Volume' => null,
                        ]],
                    ],
                    [
                        'label' => 'Sumber dan Kualitas Air Bersih',
                        'entitas' => [[
                            '0 Jenis' => null,
                            '1 Jumlah Unit' => null,
                            '2 Kondisi Rusak' => null,
                            '3 Pemanfaatan' => null,
                            '4 Kualitas' => null,
                        ]],
                    ],
                    [
                        'label' => 'Sungai',
                        'extra' => 'Jumlah Sungai',
                        'entitas' => [[
                            '0 Kondisi' => null,
                            '1 Keterangan' => null,
                        ]],
                    ],
                    [
                        'label' => 'Rawa',
                        'extra' => 'Jumlah Rawa',
                        'entitas' => [[
                            '0 Pemanfaatan' => null,
                            '1 Keterangan' => null,
                        ]],
                    ],
                    [
                        'label' => 'Pemanfaatan Danau/Waduk/Situ',
                        'extra' => 'Jumlah Danau/Waduk/Situ',
                        'entitas' => [
                            [
                                '0 Kondisi' => null,
                                '1 Keterangan' => null,
                            ]
                        ],
                    ],
                    [
                        'label' => 'Kondisi Danau/Waduk/Situ',
                        'extra' => 'Jumlah Danau/Waduk/Situ',
                        'entitas' => [[
                            '0 Kondisi' => null,
                            '1 Keterangan' => null,
                        ]],
                    ],
                    [
                        'label' => 'Air Panas',
                        'entitas' => [[
                            '0 Sumber' => null,
                            '1 Jumlah Lokasi' => null,
                            '2 Pemanfaatan Wisata' => null,
                            '3 Kepemilikian/Pengelolaan' => null
                        ]],
                    ]

                ],
                'udara' => [
                    [
                        'label' => 'Kualitas Udara',
                        'entitas' => [[
                            '0 Sumber' => null,
                            '1 Jumlah Lokasi' => null,
                            '2 Polutan' => null,
                            '3 Efek terhadap Kesehatan' => null,
                            '4 Kepemilikian' => null,
                        ]],
                    ]
                ],
                'kebisingan' => [
                    [
                        'label' => 'Kebisingan',
                        'entitas' => [[
                            '0 Tingkat Kebisingan' => null,
                            '1 Ekses Dampak Kebisingan' => null,
                            '2 Sumber Kebisingan' => null,
                            '3 Efek terhadap Penduduk' => null,
                        ]],
                    ]
                ],
                'ruang-publik' => [
                    [
                        'label' => 'Ruang Publik/Taman',
                        'entitas' => [[
                            '0 Ruang Publik/Taman' => null,
                            '1 Keberadaan' => null,
                            '2 Luas' => null,
                            '3 Tingkat Pemanfaatan' => null,
                        ]],
                    ]
                ],
                'wisata' => [
                    [
                        'label' => 'Jenis dan Jumlah Wisata',
                        'entitas' => [[
                            '0 Lokasi Tempat/Area Wisata' => null,
                            '1 Luas' => null,
                            '2 Tingkat Pemanfaatan' => null,
                        ]],
                    ],
                ]
            ],
            'sarana_prasarana' => [
                'kesehatan' => [
                    ['Puskesmas', 0, 'Buah'],
                    ['Puskesmas Pembantu', 0, 'Buah'],
                    ['Poskesdes', 0, 'Buah'],
                    ['Posyandu Dan Polindes', 0, 'Buah'],
                    ['Rumah Sakit', 0, 'Buah'],
                ],
                'pendidikan' => [
                    ['Perpustakaan', 0, 'Buah'],
                    ['Gedung Sekolah PAUD', 0, 'Buah'],
                    ['Gedung Sekolah TK', 0, 'Buah'],
                    ['Gedung Sekolah SD', 0, 'Buah'],
                    ['Gedung Sekolah SMP', 0, 'Buah'],
                    ['Gedung Sekolah SMA', 0, 'Buah'],
                    ['GedunG Perguruan Tinggi', 0, 'Buah'],
                ],
                'ibadah' => [
                    ['Masjid', 0, 'Buah'],
                    ['Mushola', 0, 'Buah'],
                    ['Gereja', 0, 'Buah'],
                    ['Pura', 0, 'Buah'],
                    ['Vihara', 0, 'Buah'],
                    ['Klenteng', 0, 'Buah'],
                ],
                'umum' => [
                    ['Olahraga', 0, 'Buah'],
                    ['Kesenian/Budaya', 0, 'Buah'],
                    ['Balai Pertemuan', 0, 'Buah'],
                    ['Sumur', 0, 'Buah'],
                    ['Pasar', 0, 'Buah'],
                    ['Lainnya', 0, 'Buah'],
                ],
                'transportasi' => [
                    ['Jalan Desa/Kelurahan', 0, 'Buah'],
                    ['Jalan Kabupaten', 0, 'Buah'],
                    ['Jalan Provinsi', 0, 'Buah'],
                    ['Jalan Nasional', 0, 'Buah'],
                    ['Tambatan Perahu', 0, 'Buah'],
                    ['Perahu Motor', 0, 'Buah'],
                    ['Lapangan Terbang', 0, 'Buah'],
                    ['Jembatan Besi', 0, 'Buah'],
                ],
                'air_bersih' => [
                    ['Hidran Air', 0, 'Buah'],
                    ['Penampung Air Hujan', 0, 'Buah'],
                    ['Pamsimas', 0, 'Buah'],
                    ['Pengolahan Air Bersih', 0, 'Buah'],
                    ['Sumur Gali', 0, 'Buah'],
                    ['Sumur Pompa', 0, 'Buah'],
                    ['Tangki Air Bersih', 0, 'Buah'],
                ],
                'sanitasi_irigasi' => [
                    ['MCK Umum', 0, 'Buah'],
                    ['Jamban Keluarga', 0, 'Buah'],
                    ['Saluran Drainase', 0, 'Buah'],
                    ['Pintu Air', 0, 'Buah'],
                    ['Saluran Irigasi', 0, 'Buah'],
                ]
            ],
            'stats' => [
                1 => [
                    'stat_kategori_id' => 1,
                    'key' => 'agama',
                    'nama' => 'Agama',
                    'slug' => 'agama',
                    'deskripsi' => 'Jumlah Penduduk Menurut Agama',
                    'status' => true,
                ],
                2 => [
                    'stat_kategori_id' => 1,
                    'key' => 'pekerjaan',
                    'nama' => 'Pekerjaan',
                    'slug' => 'pekerjaan',
                    'deskripsi' => 'Jumlah Penduduk Menurut Pekerjaan',
                    'status' => true,
                ],
                3 => [
                    'stat_kategori_id' => 1,
                    'key' =>  'pendidikan',
                    'nama' => 'Pendidikan',
                    'slug' => 'pendidikan',
                    'deskripsi' => 'Jumlah Penduduk Menurut Pendidikan',
                    'status' => true,
                ],
                4 => [
                    'stat_kategori_id' => 1,
                    'key' => 'status_perkawinan',
                    'nama' => 'Status Perkawinan',
                    'slug' => 'status-perkawinan',
                    'deskripsi' => 'Jumlah Penduduk Menurut Status Perkawinan',
                    'status' => true,
                ],
                5 => [
                    'stat_kategori_id' => 1,
                    'key' => 'status_hubungan',
                    'nama' => 'Status Hubungan Keluarga',
                    'slug' => 'status-hubungan-keluarga',
                    'deskripsi' => 'Jumlah Penduduk Menurut Status Hubungan Keluarga',
                    'status' => true,
                ],
                6 => [
                    'stat_kategori_id' => 1,
                    'key' => 'kewarganegaraan',
                    'nama' => 'Kewarganegaraan',
                    'slug' => 'kewarganegaraan',
                    'deskripsi' => 'Jumlah Penduduk Menurut Kewarganegaraan',
                    'status' => true,
                ],
                7 => [
                    'stat_kategori_id' => 1,
                    'key' => 'jenis_kelamin',
                    'nama' => 'Jenis Kelamin',
                    'slug' => 'jenis-kelamin',
                    'deskripsi' => 'Jumlah Penduduk Menurut Jenis Kelamin',
                    'status' => true,
                ],
                8 => [
                    'stat_kategori_id' => 1,
                    'key' => 'umur',
                    'nama' => 'Umur',
                    'slug' => 'umur',
                    'deskripsi' => 'Jumlah Penduduk Menurut Umur',
                    'status' => true,
                ],
                9 => [
                    'stat_kategori_id' => 1,
                    'key' => 'rentang_umur',
                    'nama' => 'Rentang Umur',
                    'slug' => 'rentang-umur',
                    'deskripsi' => 'Jumlah Penduduk Menurut Rentang Umur',
                    'status' => true,
                ],
            ],
            'lembaga' => [
                'desa' => [
                    [
                        [
                            'nama' => 'Lembaga Pemberdayaan Masyarakat Desa',
                            'slug' => 'lembaga-pemberdayaan-masyarakat-desa',
                            'deskripsi' => 'Lembaga Pemberdayaan Masyarakat Desa (LPMD) adalah lembaga yang membantu pemerintah desa dalam perencanaan, pelaksanaan, dan pengendalian pembangunan serta pemberdayaan masyarakat.',
                            'singkatan' => 'LPMD',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Ketua',
                                'Wakil Ketua',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ],
                        [
                            'nama' => 'Pemberdayaan Kesejahteraan Keluarga',
                            'slug' => 'pemberdayaan-kesejahteraan-keluarga',
                            'deskripsi' => 'Pemberdayaan Kesejahteraan Keluarga (PKK) adalah organisasi yang berfokus pada kesejahteraan keluarga melalui program pendidikan, kesehatan, dan ekonomi.',
                            'singkatan' => 'PKK',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Ketua',
                                'Wakil Ketua',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ],
                        [
                            'nama' => 'Karang Taruna',
                            'slug' => 'karang-taruna',
                            'deskripsi' => 'Karang Taruna adalah organisasi kepemudaan yang bergerak dalam bidang sosial, ekonomi, dan kebudayaan.',
                            'singkatan' => 'KT',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Ketua',
                                'Wakil Ketua',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ],
                        [
                            'nama' => 'Pos Pelayanan Terpadu',
                            'slug' => 'pos-pelayanan-terpadu',
                            'deskripsi' => 'Pos Pelayanan Terpadu (Posyandu) adalah layanan kesehatan masyarakat yang dikelola oleh kader kesehatan untuk ibu dan anak.',
                            'singkatan' => 'Posyandu',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Ketua',
                                'Wakil Ketua',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ],
                        [
                            'nama' => 'Badan Usaha Milik Desa',
                            'slug' => 'badan-usaha-milik-desa',
                            'deskripsi' => 'Badan Usaha Milik Desa (BUMDes) adalah lembaga ekonomi desa yang berfungsi untuk mengelola aset desa dan mengembangkan potensi ekonomi desa.',
                            'singkatan' => 'BUMDes',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Direktur',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ],
                        [
                            'nama' => 'Kelompok Tani',
                            'slug' => 'kelompok-tani',
                            'deskripsi' => 'Kelompok Tani adalah kelompok yang terdiri dari petani untuk meningkatkan produksi pertanian dan kesejahteraan petani.',
                            'singkatan' => 'KT',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Ketua',
                                'Wakil Ketua',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ],
                        [
                            'nama' => 'Lembaga Adat Desa',
                            'slug' => 'lembaga-adat-desa',
                            'deskripsi' => 'Lembaga Adat Desa adalah lembaga yang melestarikan adat istiadat dan budaya lokal.',
                            'singkatan' => 'LAD',
                            'logo_url' => '',
                            'alamat' => '',
                            'kategori_jabatan' => [
                                'Ketua',
                                'Wakil Ketua',
                                'Sekretaris',
                                'Bendahara',
                                'Anggota'
                            ],
                            'dokumen_id' => null
                        ]
                    ]
                ],
                'kelurahan' => [
                    [
                        'nama' => 'Lembaga Pemberdayaan Masyarakat Kelurahan',
                        'slug' => 'lembaga-pemberdayaan-masyarakat-kelurahan',
                        'deskripsi' => 'Lembaga Pemberdayaan Masyarakat Kelurahan (LPMK) adalah lembaga yang membantu pemerintah kelurahan dalam perencanaan, pelaksanaan, dan pengendalian pembangunan serta pemberdayaan masyarakat.',
                        'singkatan' => 'LPMK',
                        'logo_url' => '',
                        'alamat' => '',
                        'kategori_jabatan' => [
                            'Ketua',
                            'Wakil Ketua',
                            'Sekretaris',
                            'Bendahara',
                            'Anggota'
                        ],
                        'dokumen_id' => null
                    ],
                    [
                        'nama' => 'Pemberdayaan Kesejahteraan Keluarga',
                        'slug' => 'pemberdayaan-kesejahteraan-keluarga',
                        'deskripsi' => 'Pemberdayaan Kesejahteraan Keluarga (PKK) adalah organisasi yang berfokus pada kesejahteraan keluarga melalui program pendidikan, kesehatan, dan ekonomi.',
                        'singkatan' => 'PKK',
                        'logo_url' => '',
                        'alamat' => '',
                        'kategori_jabatan' => [
                            'Ketua',
                            'Wakil Ketua',
                            'Sekretaris',
                            'Bendahara',
                            'Anggota'
                        ],
                        'dokumen_id' => null
                    ],
                    [
                        'nama' => 'Karang Taruna',
                        'slug' => 'karang-taruna',
                        'deskripsi' => 'Karang Taruna adalah organisasi kepemudaan yang bergerak dalam bidang sosial, ekonomi, dan kebudayaan.',
                        'singkatan' => 'KT',
                        'logo_url' => '',
                        'alamat' => '',
                        'kategori_jabatan' => [
                            'Ketua',
                            'Wakil Ketua',
                            'Sekretaris',
                            'Bendahara',
                            'Anggota'
                        ],
                        'dokumen_id' => null
                    ],
                    [
                        'nama' => 'Pos Pelayanan Terpadu',
                        'slug' => 'pos-pelayanan-terpadu',
                        'deskripsi' => 'Pos Pelayanan Terpadu (Posyandu) adalah layanan kesehatan masyarakat yang dikelola oleh kader kesehatan untuk ibu dan anak.',
                        'singkatan' => 'Posyandu',
                        'logo_url' => '',
                        'alamat' => '',
                        'kategori_jabatan' => [
                            'Ketua',
                            'Wakil Ketua',
                            'Sekretaris',
                            'Bendahara',
                            'Anggota'
                        ],
                        'dokumen_id' => null
                    ],
                    [
                        'nama' => 'Lembaga Adat Kelurahan',
                        'slug' => 'lembaga-adat-kelurahan',
                        'deskripsi' => 'Lembaga Adat Kelurahan adalah lembaga yang melestarikan adat istiadat dan budaya lokal.',
                        'singkatan' => 'LAK',
                        'logo_url' => '',
                        'alamat' => '',
                        'kategori_jabatan' => [
                            'Ketua',
                            'Wakil Ketua',
                            'Sekretaris',
                            'Bendahara',
                            'Anggota'
                        ],
                        'dokumen_id' => null
                    ]
                ]
            ]
        ],
    ];
