<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $fillable = [
        'file_path', 'file_name', 'file_type',
        'file_size', 'user_id', 'attachable_id', 'attachable_type'
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
