<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * Get detailed statistics for a specific group.
     */
    public function getGroupStats(Group $group): array
    {
        $totalTasks = $group->tasks()->count();
        $completedTasks = $group->tasks()->where('status', TaskStatus::COMPLETED)->count();

        return [
            'total_tasks' => $totalTasks,
            'completed_tasks' => $completedTasks,
            'pending_tasks' => $group->tasks()->where('status', TaskStatus::PENDING)->count(),
            'in_progress_tasks' => $group->tasks()->where('status', TaskStatus::IN_PROGRESS)->count(),
            'members_count' => $group->users()->count(),

            // Completion Rate
            'completion_rate' => $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100, 2) : 0,

            // Top Performers (Users with most completed tasks)
            'top_performers' => $this->getTopPerformers($group->id),
        ];
    }

    /**
     * Get top 5 users who completed the most tasks in a group.
     */
    private function getTopPerformers(int $groupId)
    {
        return DB::table('users')
            ->join('tasks', 'users.id', '=', 'tasks.assigned_to')
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.avatar',
                DB::raw('count(tasks.id) as completed_count')
            )
            ->where('tasks.group_id', $groupId)
            ->where('tasks.status', TaskStatus::COMPLETED->value)
            ->groupBy('users.id', 'users.first_name', 'users.last_name', 'users.avatar')
            ->orderByDesc('completed_count')
            ->limit(5)
            ->get();
    }
}
