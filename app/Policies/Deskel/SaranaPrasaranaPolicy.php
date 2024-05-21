<?php

namespace App\Policies\Deskel;

use App\Models\User;
use App\Models\Deskel\SaranaPrasarana;
use Illuminate\Auth\Access\HandlesAuthorization;

class SaranaPrasaranaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_sarana::prasarana');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SaranaPrasarana $saranaPrasarana): bool
    {
        return $user->can('view_sarana::prasarana');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_sarana::prasarana');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SaranaPrasarana $saranaPrasarana): bool
    {
        return $user->can('update_sarana::prasarana');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SaranaPrasarana $saranaPrasarana): bool
    {
        return $user->can('delete_sarana::prasarana');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_sarana::prasarana');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, SaranaPrasarana $saranaPrasarana): bool
    {
        return $user->can('force_delete_sarana::prasarana');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_sarana::prasarana');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, SaranaPrasarana $saranaPrasarana): bool
    {
        return $user->can('restore_sarana::prasarana');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_sarana::prasarana');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, SaranaPrasarana $saranaPrasarana): bool
    {
        return $user->can('replicate_sarana::prasarana');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_sarana::prasarana');
    }
}
