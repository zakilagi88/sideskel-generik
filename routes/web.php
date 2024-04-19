<?php

use App\Enums\Kependudukan\AgamaType;
use App\Enums\Kependudukan\PekerjaanType;
use App\Enums\Kependudukan\PendidikanType;
use App\Exports\TemplateImport;
use App\Livewire\Berita\Display as BeritaDisplay;

use App\Livewire\Home;
use App\Livewire\KategoriBerita;
use App\Livewire\Stat\Index;
use App\Livewire\Stat\StatDisplay;
use App\Livewire\Widgets\Tables\Penduduk\AgamaTable;
use App\Models\Wilayah;
use App\Services\GenerateEnumUnionQuery;
use Illuminate\Support\Facades\DB;
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

Route::get('/preview-pdf', function () {
    return view('preview_pdf');
});




Route::get('/downloadtemplate', function () {
    return Excel::download(new TemplateImport, 'template_imports.xlsx');
})->name('downloadtemplate');

Route::get('/greeting', function () {
    // Ambil semua wilayah dengan struktur pohon
    $enum_type = GenerateEnumUnionQuery::getSubQuery(PekerjaanType::class, 'pekerjaan');

    $results = DB::statement('CALL sp_create_penduduk_view(?,?,?)', [$enum_type, 'pekerjaan', 20]);

    return $results;
    // // Kembalikan hasil filter
});

Route::get(
    'stat',
    Index::class
)->name('stat');
Route::get(
    'stat/{stat:slug}',
    StatDisplay::class
)->name('stat.display');

Route::get(
    'berita/{berita:slug}',
    BeritaDisplay::class
)->name('berita');


Route::get(
    'kat_berita/{kategori_berita:slug}',
    KategoriBerita::class
)->name('kategori_berita');



// Route::get('/admin/edit-profil/{record}', DeskelProfile::class);





// buat route untuk admin/penduduk-stats/umur admin/penduduk-stats/agama gunakan group



// Route::get(
//     '/article/tag/{tag:slug}',
//     Tag::class
// )->name('article.tag');