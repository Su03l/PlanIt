<?php

namespace App\Models;

use App\Enums\GroupRole;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GroupUser extends Pivot
{
    protected $table = 'group_user';

    protected $casts = [
        'role' => GroupRole::class, // Auto-cast string to Enum
        'joined_at' => 'datetime',
    ];
}
