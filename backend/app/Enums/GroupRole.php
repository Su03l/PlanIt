<?php

namespace App\Enums;

enum GroupRole: string
{
    case ADMIN = 'admin';       // المدير (مالك المجموعة)
    case MODERATOR = 'moderator'; // المشرف (يوزع مهام)
    case MEMBER = 'member';     // العضو (ينفذ فقط)
}
