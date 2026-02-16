<?php

namespace App\Policies;

use App\Enums\GroupRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
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
    public function view(User $user, Task $task): bool
    {
        // User must be a member of the group to view the task
        return $task->group->users()->where('user_id', $user->id)->exists();
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
    public function update(User $user, Task $task): bool
    {
        // Admin/Moderator can update any task
        // Regular member can only update tasks assigned to them

        $userRole = $task->group->users()
            ->where('user_id', $user->id)
            ->first()
            ?->pivot
            ?->role;

        if (!$userRole) return false;

        if (in_array($userRole, [GroupRole::ADMIN, GroupRole::MODERATOR])) {
            return true;
        }

        return $task->assigned_to === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        // Only Admin/Moderator can delete tasks
        $userRole = $task->group->users()
            ->where('user_id', $user->id)
            ->first()
            ?->pivot
            ?->role;

        return $userRole && in_array($userRole, [GroupRole::ADMIN, GroupRole::MODERATOR]);
    }
}
