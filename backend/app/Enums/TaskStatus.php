<?php

namespace App\Enums;

enum TaskStatus: string
{
    case PENDING = 'pending';         // في الانتظار
    case IN_PROGRESS = 'in_progress'; // جاري العمل
    case IN_REVIEW = 'in_review';     // قيد المراجعة (للمشرف)
    case COMPLETED = 'completed';     // مكتملة
    case CANCELED = 'canceled';       // ملغية
}
