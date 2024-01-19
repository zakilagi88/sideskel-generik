<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Rappasoft\LaravelAuthenticationLog\Traits\AuthenticationLoggable;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\PermissionRegistrar;
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
        'nik',
        'username',
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

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : 'https://ui-avatars.com/api/?name=' . $this->name . '&color=random&background=random&rounded=true&bold=true&size=128';
    }

    // public function assignRole($role, $wilayahId = null)
    // {
    //     $roles = $this->collectRoles([$role]);

    //     $model = $this->getModel();
    //     $teamPivot = app(PermissionRegistrar::class)->teams && !is_a($this, Permission::class) ?
    //         [app(PermissionRegistrar::class)->teamsKey => getPermissionsTeamId()] : [];

    //     if ($model->exists) {
    //         $currentRoles = $this->roles->map(fn ($role) => $role->getKey())->toArray();

    //         $this->roles()->attach(array_diff($roles, $currentRoles), $teamPivot);

    //         if (!is_null($wilayahId)) {
    //             $this->roles()->updateExistingPivot($roles, ['wilayah_id' => $wilayahId]);
    //         }

    //         $model->unsetRelation('roles');
    //     } else {
    //         $class = \get_class($model);

    //         $class::saved(function ($object) use ($roles, $model, $teamPivot, $wilayahId) {
    //             if ($model->getKey() != $object->getKey()) {
    //                 return;
    //             }

    //             $this->roles()->attach($roles, $teamPivot);

    //             if (!is_null($wilayahId)) {
    //                 $this->roles()->updateExistingPivot($roles, ['wilayah_id' => $wilayahId]);
    //             }

    //             $model->unsetRelation('roles');
    //         });
    //     }

    //     if (is_a($this, Permission::class)) {
    //         $this->forgetCachedPermissions();
    //     }

    //     return $this;
    // }

    public function wilayah(): BelongsToMany
    {
        return $this->belongsToMany(Wilayah::class, 'user_wilayahs', 'user_id', 'wilayah_id');
    }

    public function beritas()
    {
        return $this->hasMany(Berita::class);
    }


    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'nik', 'nik');
    }
}
