<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;

class UserWilayahExport implements FromCollection


{
    protected $userWilayah;

    public function __construct($userWilayah)
    {
        // Ensure $userWilayah is a collection
        $this->userWilayah = collect($userWilayah);
    }

    public function collection()
    {
        return $this->userWilayah;
    }
}
