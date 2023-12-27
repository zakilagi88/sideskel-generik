<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\Pekerjaan;
use App\Livewire\Widgets\BaseTable;
use App\Models\Penduduk;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class PekerjaanTable extends BaseTable
{
    use WithPagination;

    public $search = '';
    public $wilayahId = null;
    public $jk = null;
    public $kategoriPrioritas = 'pekerjaan';

    #[Computed()]
    public function data()
    {
        $pdd = Penduduk::allPekerjaan($this->wilayahId, $this->jk)->searchPekerjaan($this->search)->get();


        foreach (Pekerjaan::cases() as $pekerjaan) {
            $kategori[] = $pekerjaan->value;
        }

        $hasil = self::prosesDataKategori($pdd, $kategori, $this->kategoriPrioritas);

        return $hasil;
    }
}
