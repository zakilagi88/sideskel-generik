<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsuransiKesehatan extends Model
{
    use HasFactory;

    protected $table = 'asuransi_kesehatan';

    protected $primaryKey = 'as_kes_id';

    protected $fillable = [
        'as_kes_nama',
        'as_kes_nomor',
    ];
}
