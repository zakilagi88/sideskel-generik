<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class UserSLSRoles extends Model
{
    use HasFactory;

    protected $table = 'user_sls_roles';

    protected $fillable = [
        'user_id',
        'sls_id',
        'role_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function sls()
    {
        return $this->belongsTo(SLS::class, 'sls_id', 'sls_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}