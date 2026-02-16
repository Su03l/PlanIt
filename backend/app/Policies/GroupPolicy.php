<?php

namespace App\Policies;

use App\Enums\GroupRole;
use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Group $group): bool
    {
        return $group->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Group $group): bool
    {
        // Only owner or admin can update
        return $group->owner_id === $user->id ||
               $group->users()
                     ->where('user_id', $user->id)
                     ->wherePivot('role', GroupRole::ADMIN->value)
                     ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Group $group): bool
    {
        return $group->owner_id === $user->id;
    }

    /**
     * Determine whether the user can add members to the group.
     */
    public function addMember(User $user, Group $group): bool
    {
        // Owner, Admin, or Moderator can add members
        return $group->owner_id === $user->id ||
               $group->users()
                     ->where('user_id', $user->id)
                     ->wherePivotIn('role', [GroupRole::ADMIN->value, GroupRole::MODERATOR->value])
                     ->exists();
    }
}
