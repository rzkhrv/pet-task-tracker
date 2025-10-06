<?php

declare(strict_types=1);

namespace App\Enum;

enum TaskStatusEnum: string
{
    case New = 'new';
    case InProgress = 'in_progress';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
