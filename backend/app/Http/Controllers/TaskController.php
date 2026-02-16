<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Group;
use App\Services\TaskService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use HttpResponses;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        // نمرر المصفوفة كاملة للسيرفس
        $tasks = $this->taskService->getGroupTasks($group, $request->all());

        return $this->success(TaskResource::collection($tasks));
    }

    public function store(StoreTaskRequest $request, Group $group)
    {
        // البوليسي: هل يحق له إضافة مهمة؟ (فقط Admin و Moderator)
        $this->authorize('addMember', $group); // سنستخدم نفس صلاحية الإدارة حالياً

        $data = $request->validated();
        $data['created_by'] = auth()->id();

        try {
            $task = $this->taskService->createTask($group, $data);
            return $this->success(new TaskResource($task), 'Task created!', 201);
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 422);
        }
    }
}
