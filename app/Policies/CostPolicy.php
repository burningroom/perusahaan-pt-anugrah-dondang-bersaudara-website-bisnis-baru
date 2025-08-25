<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cost;
use Illuminate\Auth\Access\HandlesAuthorization;

class CostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_cost');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cost $cost): bool
    {
        return $user->can('view_cost');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_cost');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cost $cost): bool
    {
        return $user->can('update_cost');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Cost $cost): bool
    {
        return $user->can('delete_cost');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_cost');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Cost $cost): bool
    {
        return $user->can('force_delete_cost');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_cost');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Cost $cost): bool
    {
        return $user->can('restore_cost');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_cost');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Cost $cost): bool
    {
        return $user->can('replicate_cost');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_cost');
    }
}
