<?php

use App\Exports\TemplateImport;
use App\Livewire\Home;
use App\Livewire\KategoriBerita;
use App\Livewire\Pages\{AparaturPage, Berita\BeritaDisplay, BeritaPage, KeputusanPage, LembagaPage, Lembaga\LembagaDisplay, PeraturanPage, PotensiPage, SaranaPrasaranaPage, Stat\StatSDMDisplay};
use App\Livewire\Pages\Aparatur\AparaturDisplay;
use App\Livewire\Pages\Potensi\PotensiSDADisplay;
use App\Livewire\Pages\Profil\ProfilDisplay;
use App\Models\Desa\Aparatur;
use App\Models\Desa\Keputusan;
use App\Models\Desa\Peraturan;
use App\Models\Desa\PotensiSDA;
use App\Models\DesaKelurahanProfile;
use App\Models\KategoriBerita as KategoriBeritaModel;
use App\Models\Lembaga;
use App\Models\SaranaPrasarana;
use App\Models\StatSDM;
use App\Models\Tambahan;
use App\Models\Web\Berita;
use App\Settings\WebSettings;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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

Route::get('/preview-pdf', function () {
    return view('preview_pdf');
});

// $cek = app(WebSettings::class)->toArray();

// if ($cek['web_active']) {
Route::name('index.')->group(function () {
    Route::get('/', Home::class)
        ->name('beranda')
        ->linkKey(label: 'Beranda');
    Route::get('/stat/{record}', StatSDMDisplay::class)
        ->name('stat.show')
        ->linkKey(label: 'Statistik', model: StatSDM::class, modelLabel: 'nama');
    Route::get('/stat/tambahan/{record}', StatSDMDisplay::class)
        ->name('stat.tambahan.show')
        ->linkKey(label: 'Statistik Tambahan', model: Tambahan::class, modelLabel: 'nama');
    Route::get('/berita', BeritaPage::class)
        ->name('berita')
        ->linkKey(label: 'Berita List');
    Route::get('/berita/{record}', BeritaDisplay::class)
        ->name('berita.show')
        ->linkKey(label: 'Berita', model: Berita::class, modelLabel: 'title');
    Route::get('/peraturan', PeraturanPage::class)
        ->name('peraturan')
        ->linkKey(label: 'Peraturan List');
    Route::get('/keputusan', KeputusanPage::class)
        ->name('keputusan')
        ->linkKey(label: 'Keputusan List');
    Route::get('/lembaga', LembagaPage::class)
        ->name('lembaga')
        ->linkKey(label: 'Lembaga List');
    Route::get('/lembaga/{record}', LembagaDisplay::class)
        ->name('lembaga.show')
        ->linkKey(label: 'Lembaga', model: Lembaga::class, modelLabel: 'nama');
    Route::get('/aparatur', AparaturPage::class)->name('aparatur')
        ->linkKey(label: 'Aparatur List');
    Route::get('/aparatur/{record}', AparaturDisplay::class)
        ->name('aparatur.show')
        ->linkKey(label: 'Aparatur', model: Aparatur::class, modelLabel: 'nama');
    Route::get('/potensi', PotensiPage::class)
        ->name('potensi')
        ->linkKey(label: 'Potensi List');
    Route::get('/potensi/sda/{record}', PotensiSDADisplay::class)
        ->name('potensi.sda.show')
        ->linkKey(label: 'Potensi', model: PotensiSDA::class, modelLabel: 'jenis');
    Route::get('/profil-deskel/{record}', ProfilDisplay::class)
        ->name('profil.show')
        ->linkKey(label: 'Profil Desa', model: DesaKelurahanProfile::class, modelLabel: 'nama');
    // Route::get('/sarana-prasarana', SaranaPrasaranaPage::class)
    //     ->name('sarana.prasarana')
    //     ->linkKey(label: 'Sarana Prasarana', model: SaranaPrasarana::class, modelLabel: 'jenis');
});
// }






Route::get('/indeks-desa', function () {
    $response = Http::get('https://idm.kemendesa.go.id/open/api/desa/rumusanpokok/6303022016/2023');
    $htmlContent = $response->body();
    return view('indeks-desa', compact('htmlContent'));
});



Route::get('/tests', function () {
    $default_tables = config('app_data.default_tables');
    // $cek->site_init;
    dd($default_tables);
});



// Route::get(
//     '/article/tag/{tag:slug}',
//     Tag::class
// )->name('article.tag');