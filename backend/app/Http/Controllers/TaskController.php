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
    // this for http responses
    use HttpResponses;

    // this for task service
    protected TaskService $taskService;

    // this for task service constructor
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    // this for show all tasks request and return response
    public function index(Request $request, Group $group)
    {
        $this->authorize('view', $group);

        // نمرر المصفوفة كاملة للسيرفس
        $tasks = $this->taskService->getGroupTasks($group, $request->all());

        return $this->success(TaskResource::collection($tasks));
    }

    // this for create task request and return response
    public function store(StoreTaskRequest $request, Group $group)
    {
        $this->authorize('addMember', $group); 

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
