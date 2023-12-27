<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\RentangUmur;
use App\Livewire\Widgets\BaseTable;
use App\Models\Penduduk;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class RentangUmurTable extends BaseTable
{
    use WithPagination;

    public $search = '';
    public $wilayahId = null;
    public $jk = null;
    public $kategoriPrioritas = 'rentang_umur';

    #[Computed()]
    public function data()
    {
        $pdd = Penduduk::allRentangUmur($this->wilayahId, $this->jk)->get();

        foreach (RentangUmur::cases() as $rentang_umur) {
            $kategori[] = $rentang_umur->value;
        }
        $hasil = self::prosesDataKategori($pdd, $kategori, $this->kategoriPrioritas);

        return $hasil;
    }
}
