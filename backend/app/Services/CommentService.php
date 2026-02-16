<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comment;

class CommentService
{
    /**
     * Add a comment to any model (Task, Group, etc.)
     */
    public function addComment(Model $model, int $userId, string $body): Comment
    {
        $comment = $model->comments()->create([
            'body' => $body,
            'user_id' => $userId
        ]);

        // إرسال التنبيهات
        $this->sendNotifications($model, $userId, $body);

        return $comment;
    }

    private function sendNotifications(Model $model, int $senderId, string $body)
    {
        $sender = User::find($senderId);

        // إذا كان التعليق على مهمة
        if ($model instanceof Task) {
            // تنبيه للمسؤول عن المهمة (إذا لم يكن هو المعلق)
            if ($model->assigned_to && $model->assigned_to !== $senderId) {
                $assignee = User::find($model->assigned_to);
                $assignee->notify(new SystemNotification(
                    "New Comment on Task",
                    $sender->full_name . " commented on task: " . $model->title,
                    "comment",
                    "/tasks/" . $model->uuid
                ));
            }

            // تنبيه لمنشئ المهمة (إذا لم يكن هو المعلق ولم يكن هو المسؤول)
             if ($model->created_by !== $senderId && $model->created_by !== $model->assigned_to) {
                $creator = User::find($model->created_by);
                $creator->notify(new SystemNotification(
                    "New Comment on Task",
                    $sender->full_name . " commented on task: " . $model->title,
                    "comment",
                    "/tasks/" . $model->uuid
                ));
            }
        }
    }
}
