<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\Agama;
use App\Livewire\Widgets\BaseTable;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class AgamaTable extends BaseTable
{
    use WithPagination;

    public $search = '';
    public $wilayahId = null;
    public $jk = null;
    public $kategoriPrioritas = 'agama';

    #[Computed()]
    public function data()
    {
        $pdd = Penduduk::allAgama($this->wilayahId, $this->jk)->searchAgama($this->search)->get();


        foreach (Agama::cases() as $pekerjaan) {
            $kategori[] = $pekerjaan->value;
        }

        $hasil = self::prosesDataKategori($pdd, $kategori, $this->kategoriPrioritas);

        return $hasil;
    }
}
