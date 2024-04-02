<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class APBDes extends Model
{
    use HasFactory;
    use HasRecursiveRelationships;


    protected $table = 'apbdes';

    protected $primaryKey = 'apbdes_id';

    protected $fillable = [
        'tahun',
        'komponen',
        'komponen_id',
        'nilai',
        'realisasi',
    ];

    public function getParentKeyName()
    {
        return 'komponen_id';
    }

    public function getLocalKeyName()
    {
        return 'apbdes_id';
    }
}
