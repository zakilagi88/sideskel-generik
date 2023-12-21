<?php

use App\Livewire\Article\Display;
use App\Livewire\Article\Grid;
use App\Livewire\Category;
use App\Livewire\Home;
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
    '/{article:slug}',
    Display::class
)->name('article');


Route::get(
    '/{category:slug}',
    Category::class
)->name('category');

// buat route untuk admin/penduduk-stats/umur admin/penduduk-stats/agama gunakan group



// Route::get(
//     '/article/tag/{tag:slug}',
//     Tag::class
// )->name('article.tag');