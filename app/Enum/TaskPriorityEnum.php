<?php

declare(strict_types=1);

namespace App\Enum;

enum TaskPriorityEnum: string
{
    case High = 'high';
    case Normal = 'normal';
    case Low = 'low';
}
