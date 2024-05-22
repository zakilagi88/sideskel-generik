<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;

class UserWilayahExport implements FromArray
{
    protected $users;

    public function __construct(array $users)
    {
        $this->users = $users;
    }

    public function array(): array
    {
        return $this->users;
    }
}
