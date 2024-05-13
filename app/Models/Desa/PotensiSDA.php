<?php

namespace App\Models\Desa;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotensiSDA extends Model
{
    use HasFactory;

    protected $table = 'potensi_sdas';

    protected $fillable =
    [
        'jenis',
        'data'
    ];

    protected $casts =
    [
        'data' => 'array'
    ];

    public function getLinkKey(): string
    {
        return $this->jenis;
    }

    public function getRouteKeyName()
    {
        return 'jenis';
    }
}