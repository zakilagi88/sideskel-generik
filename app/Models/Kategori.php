<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'kategori';
    protected $primaryKey = 'kategori_id';
    protected $fillable = [
        'kategori_nama',
        'kategori_slug',
    ];

    
}