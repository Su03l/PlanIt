<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\CommentService;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class TaskCommentController extends Controller
{
    // this for return response
    use HttpResponses;

    // this for comment service
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function __invoke(Request $request, Task $task)
    {
        $request->validate(['body' => 'required|string|max:2000']);

        // TODO: Add authorization check here (e.g., user must be member of the group)
        // $this->authorize('view', $task);

        $comment = $this->commentService->addComment(
            $task,
            auth()->id(),
            $request->body
        );

        return $this->success($comment, 'Comment added successfully.');
    }
}
