<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\SatuanUmur;
use App\Livewire\Widgets\BaseTable;
use App\Models\Penduduk;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class SatuanUmurTable extends BaseTable
{
    use WithPagination;

    public $search = '';
    public $wilayahId = null;
    public $jk = null;
    public $kategoriPrioritas = 'satuan_umur';

    #[Computed()]
    public function data()
    {
        $pdd = Penduduk::allSatuanUmur($this->wilayahId, $this->jk)->get();

        foreach (SatuanUmur::cases() as $satuan_umur) {
            $kategori[] = $satuan_umur->value;
        }
        $hasil = self::prosesDataKategori($pdd, $kategori, $this->kategoriPrioritas);

        return $hasil;
    }
}
