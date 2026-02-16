<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\AttachmentService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TaskAttachmentController extends Controller
{
    use HttpResponses;

    public function __invoke(Request $request, Task $task, AttachmentService $service)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // حد أقصى 10MB
        ]);

        // TODO: Add authorization check here
        // $this->authorize('view', $task);

        $attachment = $service->upload($task, $request->file('file'), auth()->id());

        return $this->success($attachment, 'File uploaded and attached to task.');
    }
}
