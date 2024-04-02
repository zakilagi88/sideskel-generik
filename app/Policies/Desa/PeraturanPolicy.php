<?php

namespace App\Policies\Desa;

use App\Models\User;
use App\Models\Desa\Peraturan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PeraturanPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_peraturan');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Desa\Peraturan  $peraturan
     * @return bool
     */
    public function view(User $user, Peraturan $peraturan): bool
    {
        return $user->can('view_peraturan');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_peraturan');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Desa\Peraturan  $peraturan
     * @return bool
     */
    public function update(User $user, Peraturan $peraturan): bool
    {
        return $user->can('update_peraturan');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Desa\Peraturan  $peraturan
     * @return bool
     */
    public function delete(User $user, Peraturan $peraturan): bool
    {
        return $user->can('delete_peraturan');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_peraturan');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Desa\Peraturan  $peraturan
     * @return bool
     */
    public function forceDelete(User $user, Peraturan $peraturan): bool
    {
        return $user->can('{{ ForceDelete }}');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('{{ ForceDeleteAny }}');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Desa\Peraturan  $peraturan
     * @return bool
     */
    public function restore(User $user, Peraturan $peraturan): bool
    {
        return $user->can('restore_peraturan');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_peraturan');
    }

    /**
     * Determine whether the user can replicate.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Desa\Peraturan  $peraturan
     * @return bool
     */
    public function replicate(User $user, Peraturan $peraturan): bool
    {
        return $user->can('{{ Replicate }}');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('{{ Reorder }}');
    }

}
