<?php

declare(strict_types=1);

namespace App\Dto\Service\TaskNotification;

use App\Enum\TaskNotificationTypeEnum;

class ProcessTaskNotificationDto
{
    public function __construct(
        public int $taskId,
        public TaskNotificationTypeEnum $type
    ) {}
}
