<?php

namespace App\Enums;

enum TaskPriority: string
{
    case LOW = 'low';           // منخفضة
    case MEDIUM = 'medium';     // متوسطة
    case HIGH = 'high';         // عالية
    case CRITICAL = 'critical'; // حرجة (تولع أحمر)
}
