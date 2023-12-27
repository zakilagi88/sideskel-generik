<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\Perkawinan;
use App\Livewire\Widgets\BaseTable;
use App\Models\Penduduk;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class PerkawinanTable extends BaseTable
{
    use WithPagination;

    public $search = '';
    public $wilayahId = null;
    public $jk = null;
    public $kategoriPrioritas = 'status_perkawinan';

    #[Computed()]
    public function data()
    {
        $pdd = Penduduk::allPerkawinan($this->wilayahId, $this->jk)->searchPerkawinan($this->search)->get();


        foreach (Perkawinan::cases() as $perkawinan) {
            $kategori[] = $perkawinan->value;
        }

        $hasil = self::prosesDataKategori($pdd, $kategori, $this->kategoriPrioritas);

        return $hasil;
    }
}
