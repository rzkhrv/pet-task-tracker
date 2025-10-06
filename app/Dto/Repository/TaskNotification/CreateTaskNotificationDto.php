<?php

declare(strict_types=1);

namespace App\Dto\Repository\TaskNotification;

class CreateTaskNotificationDto
{
    public function __construct(
        public int $taskId,
        public int $userId,
        public string $message
    ) {}
}
