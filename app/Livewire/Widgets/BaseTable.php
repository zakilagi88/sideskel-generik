<?php

namespace App\Livewire\Widgets;

use App\Models\Wilayah;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class BaseTable extends Component
{

    use WithPagination;

    public $search = '';
    public $perPage = 5;
    public $wilayahId = null;
    public $jk = null;


    #[Computed()]
    public function data()
    {
        return collect([]);
    }

    public function render()
    {
        $wilayah = Wilayah::all();
        $hasil = $this->data();
        $data = $hasil['data'] ?? collect([]);
        $totals = $hasil['totals'] ?? 0;

        $data = $this->paginate($data, $this->perPage);
        $headers = array_keys($data->first() ?? []);

        return view(
            'livewire.widgets.base-table',
            compact('wilayah', 'data', 'headers', 'totals')
        );
    }

    public function prosesDataKategori($data, $kategori, $kategoriPrioritas)
    {
        $sementara = self::kelompokkanDataKategoriJK($data, $kategoriPrioritas);
        $kategoriPrioritas = strtoupper($kategoriPrioritas);

        $hasil = [];
        $totalLakiLaki = 0;
        $totalPerempuan = 0;

        foreach ($kategori as $kategoriItem) {
            $lakiLaki = $sementara['LAKI-LAKI'][$kategoriItem] ?? 0;
            $perempuan = $sementara['PEREMPUAN'][$kategoriItem] ?? 0;
            $total = $lakiLaki + $perempuan;

            $hasil[] = [
                $kategoriPrioritas => $kategoriItem,
                'LAKI-LAKI' => $lakiLaki,
                'PEREMPUAN' => $perempuan,
                'TOTAL' => $total,
            ];

            $totalLakiLaki += $lakiLaki;
            $totalPerempuan += $perempuan;
        }

        $totals = $totalLakiLaki + $totalPerempuan;

        return [
            'data' => $hasil,
            'totals' => $totals,
        ];
    }



    public function kelompokkanDataKategoriJK($penduduk, $kategoriPrioritas)
    {
        $sementara = ['LAKI-LAKI' => [], 'PEREMPUAN' => []];

        foreach ($penduduk as $individu) {
            $jenisKelamin = is_object($individu->jenis_kelamin) ? $individu->jenis_kelamin->value : $individu->jenis_kelamin;
            $kategori = is_object($individu->$kategoriPrioritas) ? $individu->$kategoriPrioritas->value : $individu->$kategoriPrioritas;
            $total = $individu->total;

            if (!isset($sementara[$jenisKelamin][$kategori])) {
                $sementara[$jenisKelamin][$kategori] = 0;
            }

            $sementara[$jenisKelamin][$kategori] += $total;
        }

        return $sementara;
    }


    public static function paginate(
        array|Collection $items,
        ?int $perPage = null,
        ?int $page = null,
        array $options = [],
        ?string $path = null
    ): LengthAwarePaginator {
        $perPage ??= 50;
        $page ??= Paginator::resolveCurrentPage(default: 1);
        $path ??= Paginator::resolveCurrentPath();

        $items = $items instanceof Collection ? $items : Collection::make($items);

        $paginator = new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );

        if (!blank($path)) {
            $paginator = $paginator->setPath($path);
        }

        return $paginator;
    }
}
