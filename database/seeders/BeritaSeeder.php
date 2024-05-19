<?php

namespace Database\Seeders;

use App\Models\KategoriBerita;
use App\Models\Web\Berita;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Berita Kategori 1

        $dataKategori = [
            [
                'kategori_berita_id' => 1,
                'nama' => 'Kelurahan',
                'slug' => 'kelurahan',
            ],
            [
                'kategori_berita_id' => 2,
                'nama' => 'Bantuan',
                'slug' => 'bantuan',
            ],
            [
                'kategori_berita_id' => 3,
                'nama' => 'Pengumuman',
                'slug' => 'pengumuman',
            ],
        ];

        foreach ($dataKategori as $kategori) {
            KategoriBerita::create($kategori);
        }

        $berita = [
            [
                'berita_id' => 1,
                'user_id' => 1,
                'kategori_berita_id' => 1,
                'title' => 'Berita Terbaik',
                'slug' => 'berita-terbaik',
                'gambar' => '"berita\/01HY68GE2X44H81380YDMM13WM.gif"',
                'body' => '**Lorem ipsum dolor sit amet,** consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet consectetur adipiscing elit.',
                'meta_description' => 'Berita terbaik di Kelurahan A',
                'meta_tags' => 'berita, terbaik, kelurahan, a',
                'scheduled_for' => '2021-12-31 00:00:00',
                'published_at' => '2021-12-31 00:00:00',
                'status' => 'PUBLISH',
                'created_at' => '2021-12-31 00:00:00',
                'updated_at' => '2021-12-31 00:00:00',
            ],
            [
                'berita_id' => 2,
                'user_id' => 1,
                'kategori_berita_id' => 2,
                'title' => 'Berita Terbaik',
                'slug' => 'berita-terbaik',
                'gambar' => '"berita\/01HY68GE2X44H81380YDMM13WM.gif"',
                'body' => '**Lorem ipsum dolor sit amet,** consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet consectetur adipiscing elit.',
                'meta_description' => 'Berita terbaik di Kelurahan A',
                'meta_tags' => 'berita, terbaik, kelurahan, a',
                'scheduled_for' => '2021-12-31 00:00:00',
                'published_at' => '2021-12-31 00:00:00',
                'status' => 'PUBLISH',
                'created_at' => '2021-12-31 00:00:00',
                'updated_at' => '2021-12-31 00:00:00',
            ],
            [
                'berita_id' => 3,
                'user_id' => 1,
                'kategori_berita_id' => 3,
                'title' => 'Berita Terbaik',
                'slug' => 'berita-terbaik',
                'gambar' => '"berita\/01HY68GE2X44H81380YDMM13WM.gif"',
                'body' => '**Lorem ipsum dolor sit amet,** consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

> Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet consectetur adipiscing elit.',
                'meta_description' => 'Berita terbaik di Kelurahan A',
                'meta_tags' => 'berita, terbaik, kelurahan, a',
                'scheduled_for' => '2021-12-31 00:00:00',
                'published_at' => '2021-12-31 00:00:00',
                'status' => 'PUBLISH',
                'created_at' => '2021-12-31 00:00:00',
                'updated_at' => '2021-12-31 00:00:00',
            ],
        ];

        foreach ($berita as $item) {
            Berita::create($item);
        }
    }
}
