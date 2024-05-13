<?php

use App\Exports\TemplateImport;
use App\Livewire\Home;
use App\Livewire\KategoriBerita;
use App\Livewire\Pages\{Berita\BeritaDisplay, BeritaPage, KeputusanPage, LembagaPage, Lembaga\LembagaDisplay, PeraturanPage, Stat\StatSDMDisplay};
use App\Models\Desa\Keputusan;
use App\Models\Desa\Peraturan;
use App\Models\KategoriBerita as KategoriBeritaModel;
use App\Models\Lembaga;
use App\Models\StatSDM;
use App\Models\Tambahan;
use App\Models\Web\Berita;
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

Route::name('index.')->group(function () {
    Route::get('/', Home::class)->name('beranda')
        ->linkKey(label: 'Beranda');
    Route::get('/stat/{record}', StatSDMDisplay::class)->name('stat.show')
        ->linkKey(label: 'Statistik', model: StatSDM::class, modelLabel: 'nama');
    Route::get('/stat/tambahan/{record}', StatSDMDisplay::class)->name('stat.tambahan.show')
        ->linkKey(label: 'Statistik Tambahan', model: Tambahan::class, modelLabel: 'nama');
    Route::get('/berita', BeritaPage::class)->name('berita')
        ->linkKey(label: 'Berita List');
    Route::get('/berita/{record}', BeritaDisplay::class)->name('berita.show')
        ->linkKey(label: 'Berita', model: Berita::class, modelLabel: 'title');
    Route::get('/kat_berita/{kategori_berita:slug}', KategoriBerita::class)->name('kategori_berita')
        ->linkKey(label: 'Kategori Berita', model: KategoriBeritaModel::class, modelLabel: 'name');
    Route::get('/peraturan', PeraturanPage::class)->name('peraturan')
        ->linkKey(label: 'Peraturan', model: Peraturan::class);
    Route::get('/keputusan', KeputusanPage::class)->name('keputusan')
        ->linkKey(label: 'Keputusan', model: Keputusan::class);
    Route::get('/lembaga', LembagaPage::class)->name('lembaga')
        ->linkKey(label: 'Lembaga', model: Lembaga::class);
    Route::get('/lembaga/{record}', LembagaDisplay::class)->name('lembaga.show')
        ->linkKey(label: 'Lembaga', model: Lembaga::class, modelLabel: 'nama');
});

Route::get('/downloadtemplate', function () {
    return Excel::download(new TemplateImport, 'template_imports.xlsx');
})->name('downloadtemplate');


Route::get('/indeks-desa', function () {
    $response = Http::get('https://idm.kemendesa.go.id/open/api/desa/rumusanpokok/6303022016/2023');
    $htmlContent = $response->body();
    return view('indeks-desa', compact('htmlContent'));
});

Route::get('/preview-pdf', function () {
    return view('preview_pdf');
});

Route::get('/tests', function () {
    $default_tables = config('app_data.default_tables');
    foreach ($default_tables['sarana_prasarana'] as $jenis => $value) {
        dd($jenis, $value);
    }
});



// Route::get(
//     '/article/tag/{tag:slug}',
//     Tag::class
// )->name('article.tag');