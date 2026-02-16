<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Group;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Database\Eloquent\Builder;

class TaskService
{
    public function createTask(Group $group, array $data): Task
    {
        // التأكد أن الموظف المسؤول عضو في المجموعة
        if (isset($data['assigned_to'])) {
            if (!$group->users()->where('user_id', $data['assigned_to'])->exists()) {
                throw new \Exception("The assigned user must be a member of this group.");
            }
        }

        $task = $group->tasks()->create($data);

        // إرسال تنبيه للمستخدم المسند إليه المهمة
        if ($task->assigned_to && $task->assigned_to !== auth()->id()) {
            $assignee = User::find($task->assigned_to);
            $assignee->notify(new SystemNotification(
                "New Task Assigned",
                auth()->user()->full_name . " assigned you to task: " . $task->title,
                "task",
                "/tasks/" . $task->uuid
            ));
        }

        return $task;
    }

    public function updateStatus(Task $task, string $newStatus): Task
    {
        $task->update(['status' => $newStatus]);
        return $task;
    }

    /**
     * جلب المهام مع الفلترة والبحث
     */
    public function getGroupTasks(Group $group, array $filters)
    {
        return Task::where('group_id', $group->id)
            ->with(['assignee', 'creator']) // Eager loading لتقليل الاستعلامات
            ->when(isset($filters['search']), function (Builder $query) use ($filters) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                      ->orWhere('description', 'like', '%' . $filters['search'] . '%');
                });
            })
            ->when(isset($filters['status']), function (Builder $query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['priority']), function (Builder $query) use ($filters) {
                $query->where('priority', $filters['priority']);
            })
            ->when(isset($filters['assigned_to']), function (Builder $query) use ($filters) {
                $query->where('assigned_to', $filters['assigned_to']);
            })
            ->when(isset($filters['due_before']), function (Builder $query) use ($filters) {
                $query->where('due_date', '<=', $filters['due_before']);
            })
            ->latest()
            ->paginate(15);
    }
}
