<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\Pendidikan;
use App\Livewire\Widgets\BaseTable;
use App\Models\Penduduk;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

class PendidikanTable extends BaseTable
{
    use WithPagination;

    public $search = '';
    public $wilayahId = null;
    public $jk = null;
    public $kategoriPrioritas = 'pendidikan';

    #[Computed()]
    public function data()
    {
        $pdd = Penduduk::allPendidikan($this->wilayahId, $this->jk)->searchPendidikan($this->search)->get();


        foreach (Pendidikan::cases() as $pendidikan) {
            $kategori[] = $pendidikan->value;
        }

        $hasil = self::prosesDataKategori($pdd, $kategori, $this->kategoriPrioritas);

        return $hasil;
    }
}
