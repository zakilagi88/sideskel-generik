<?php

use App\Livewire\Berita\Display;

use App\Livewire\Home;
use App\Livewire\KategoriBerita;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', Home::class)->name('home');


Route::get(
    '/{berita:slug}',
    Display::class
)->name('berita');


Route::get(
    '/{kategori_berita:slug}',
    KategoriBerita::class
)->name('kategori_berita');

// buat route untuk admin/penduduk-stats/umur admin/penduduk-stats/agama gunakan group



// Route::get(
//     '/article/tag/{tag:slug}',
//     Tag::class
// )->name('article.tag');