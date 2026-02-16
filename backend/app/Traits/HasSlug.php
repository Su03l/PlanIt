<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    /**
     * هذه الدالة تعمل تلقائياً عند استخدام الموديل (Boot Method)
     */
    protected static function bootHasSlug()
    {
        static::creating(function ($model) {
            // إذا السلاق مو موجود، اصنعه من الاسم أو العنوان
            if (empty($model->slug)) {
                $source = $model->name ?? $model->title ?? 'item';
                $model->slug = Str::slug($source) . '-' . rand(1000, 9999);
            }
        });
    }
}
