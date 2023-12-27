<?php

namespace App\Livewire\Widgets\Table;

use App\Enum\Penduduk\RentangUmur;
use App\Models\Penduduk;
use App\Models\Wilayah;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UmurTable extends Component
{

    public $wilayahId = null;
    public $jk = null;

    #[Computed()]
    public function umurs()
    {
        $pdd = Penduduk::groupUJ($this->wilayahId)
            ->get();
        // dd($pdd);
        $totals = self::getRentangUmurData($pdd);

        $this->dispatch('umurs', $totals);

        return $totals;
    }


    public function render()
    {
        $wilayah = Wilayah::all();
        return view('livewire.widgets.table.umur-table', compact('wilayah'));
    }

    public function groupByGender($penduduk)
    {
        $totals = ['LAKI-LAKI' => [], 'PEREMPUAN' => []];

        foreach ($penduduk as $individu) {
            $jenisKelamin = $individu->jenis_kelamin->value;
            $kelompokUmur = $individu->kelompok_umur;
            $total = $individu->total;

            if (!isset($totals[$jenisKelamin][$kelompokUmur])) {
                $totals[$jenisKelamin][$kelompokUmur] = 0;
            }

            $totals[$jenisKelamin][$kelompokUmur] += $total;
        }

        return ($totals);
    }


    public function prepareData($totals, $categories)
    {
        $preparedData = ['LAKI-LAKI' => [], 'PEREMPUAN' => [], 'TOTAL' => []];

        foreach (['LAKI-LAKI', 'PEREMPUAN'] as $gender) {
            foreach ($categories as $category) {
                $preparedData[$gender][$category] = $totals[$gender][$category] ?? 0;
            }
        }

        // Menambahkan kolom "TOTAL" yang merupakan penjumlahan dari setiap baris di dalam array "LAKI-LAKI" dan "PEREMPUAN"
        $preparedData['TOTAL'] = array_map(function ($laki, $perempuan) {
            return $laki + $perempuan;
        }, $preparedData['LAKI-LAKI'], $preparedData['PEREMPUAN']);

        $preparedData['UMUR'] = $categories;
        $preparedData['LAKI-LAKI'] = array_values($preparedData['LAKI-LAKI']);
        $preparedData['PEREMPUAN'] = array_values($preparedData['PEREMPUAN']);
        $preparedData['TOTAL'] = array_values($preparedData['TOTAL']);

        return $preparedData;
    }

    public function getRentangUmurData($data): array
    {

        $totals = self::groupByGender($data);
        $Kategori = [
            "0-4", "5-9", "10-14", "15-19", "20-24", "25-29",
            "30-34", "35-39", "40-44", "45-49", "50-54", "55-59",
            "60-64", "65-69", "70-74", "75+"
        ];


        $totals = self::prepareData($totals, $Kategori);

        return $totals;
    }
}