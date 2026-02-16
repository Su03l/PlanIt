<?php

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory, SoftDeletes, HasSlug, HasUuid;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'owner_id',
    ];

// علاقة: المجموعة تملك أعضاء كثيرين
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'group_user')
            ->using(GroupUser::class) // Use custom pivot model
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    // علاقة: المجموعة لها مالك واحد (Admin المؤسس)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
