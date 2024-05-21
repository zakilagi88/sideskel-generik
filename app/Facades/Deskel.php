<?php

namespace App\Facades;

use App\Models\Deskel\DesaKelurahanProfile;
use Illuminate\Support\Facades\Facade;

class Deskel extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'deskel';
    }
}
