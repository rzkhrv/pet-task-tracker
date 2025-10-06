<?php

declare(strict_types=1);

namespace App\Enum;

enum TaskNotificationTypeEnum: string
{
    case StatusChanged = 'status_changed';

    case TaskAssigned = 'task_assigned';

    case Overdue = 'overdue';
}
