<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// use Intervention\Image\Laravel\Facades\Image; // Uncomment after installing package

trait UploadFiles
{
    /**
     * رفع ملف جديد
     * @param UploadedFile $file الملف المرفوع
     * @param string $folder المجلد (users, groups, tasks)
     * @param string $disk الديسك (public, s3)
     */
    public function uploadFile(UploadedFile $file, $folder = 'images', $disk = 'public')
    {
        // إذا كان الملف صورة، نستخدم Intervention Image
        if (str_starts_with($file->getMimeType(), 'image/')) {
             $filename = Str::random(20) . '.webp';
             $path = $folder . '/' . $filename;

             // ملاحظة: هذا الكود يتطلب تثبيت intervention/image
             // Image::read($file)
             //     ->cover(800, 800) // Resize logic can be customized
             //     ->toWebp(80)
             //     ->save(storage_path('app/public/' . $path));

             // حالياً سنستخدم الطريقة التقليدية حتى يتم تثبيت المكتبة
             $path = $file->storeAs($folder, $filename, $disk);
             return $path;
        }

        // للملفات الأخرى (PDF, Docs)
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, $disk);

        return $path;
    }

    /**
     * حذف ملف قديم (للتنظيف)
     */
    public function deleteFile($path, $disk = 'public')
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
