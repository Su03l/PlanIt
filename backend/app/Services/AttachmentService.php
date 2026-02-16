<?php

namespace App\Services;

use App\Models\Attachment;
use App\Traits\UploadFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class AttachmentService
{
    use UploadFiles;

    public function upload(Model $model, UploadedFile $file, int $userId): Attachment
    {
        // 1. رفع الملف فعلياً باستخدام الـ Trait
        // نستخدم اسم الموديل كاسم للمجلد (مثلاً: tasks, comments)
        $folder = strtolower(class_basename($model)) . '/attachments';
        $path = $this->uploadFile($file, $folder, 'public');

        // 2. تسجيل البيانات في جدول المرفقات
        return $model->attachments()->create([
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getClientOriginalExtension(),
            'file_size' => $file->getSize(),
            'user_id'   => $userId,
        ]);
    }
}
