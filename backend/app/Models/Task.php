<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskPriority;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Task extends Model
{
    use SoftDeletes, HasUuid;

    protected $fillable = [
        'title', 'description', 'group_id', 'created_by',
        'assigned_to', 'status', 'priority', 'due_date'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'priority' => TaskPriority::class,
        'due_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::addGlobalScope('member_of_group', function (Builder $builder) {
            if (auth()->check()) {
                // المهمة لازم تكون تابعة لمجموعة المستخدم عضو فيها
                // نستخدم whereHas للتحقق من علاقة المستخدم بالمجموعات
                $builder->whereHas('group.users', function ($query) {
                    $query->where('users.id', auth()->id());
                });
            }
        });
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
