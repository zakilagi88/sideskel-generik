<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, AuthenticationLoggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_url',
        'settings'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'settings' => 'array'
    ];

    /**
     * The attributes that should be appended.
     *
     * @var array<int, string>
     */

    // protected $appends = [
    //     'profile_photo_url',
    // ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function wilayahRoles()
    {
        return $this->belongsToMany(wilayah::class, 'user_wilayah_roles', 'user_id', 'wilayah_id')->as('wilayah')->withPivot('role_id')->withTimestamps();
    }

    public function kelurahan()
    {
        return $this->wilayahRoles()->first()->kel_groups()->first();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : 'https://ui-avatars.com/api/?name=' . $this->name . '&color=random&background=random&rounded=true&bold=true&size=128';
    }
}
