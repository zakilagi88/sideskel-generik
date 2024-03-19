<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriStunting extends Model
{
    use HasFactory;

    protected $table = 'kategori_stuntings';

    protected $primaryKey = 'id';
}
