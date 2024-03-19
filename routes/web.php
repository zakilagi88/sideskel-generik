<?php

use App\Exports\TemplateImport;
use App\Filament\Pages\DeskelProfile;
use App\Livewire\Berita\Display;

use App\Livewire\Home;
use App\Livewire\KategoriBerita;
use App\Models\Wilayah;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;

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


Route::get('/indeks-desa', function () {
    $response = Http::get('https://idm.kemendesa.go.id/open/api/desa/rumusanpokok/6303022016/2023');
    $htmlContent = $response->body();
    return view('indeks-desa', compact('htmlContent'));
});





Route::get('/downloadtemplate', function () {
    return Excel::download(new TemplateImport, 'template_imports.xlsx');
})->name('downloadtemplate');

Route::get('/greeting', function () {
    // Ambil semua wilayah dengan struktur pohon
    $wilayahTree = Wilayah::tree()->get();

    $wilayahFlat = $wilayahTree->where('depth', 1)->pluck('wilayah_nama', 'wilayah_id')->toArray();
    // // Filter data untuk mendapatkan semua wilayah pada kedalaman 2
    // $wilayahKedalaman2 = $wilayahFlat->filter(function ($node) {
    //     return $node->depth === 2;
    // });

    // // Kembalikan hasil filter
    return $wilayahFlat;
});


Route::get(
    '/{berita:slug}',
    Display::class
)->name('berita');


Route::get(
    '/{kategori_berita:slug}',
    KategoriBerita::class
)->name('kategori_berita');



// Route::get('/admin/edit-profil/{record}', DeskelProfile::class);





// buat route untuk admin/penduduk-stats/umur admin/penduduk-stats/agama gunakan group



// Route::get(
//     '/article/tag/{tag:slug}',
//     Tag::class
// )->name('article.tag');