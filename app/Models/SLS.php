<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SLS extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $table = 'sls';
    protected $primaryKey = 'sls_id';
    protected $fillable = [
        'sls_kode',
        

    ];

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }

    public function rw()
    {
        return $this->belongsTo(RW::class);
    }

    public function rt()
    {
        return $this->belongsTo(RT::class);
    }
}
