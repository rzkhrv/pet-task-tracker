<?php

declare(strict_types=1);

namespace App\Dto\Service\Task;

use App\Enum\TaskStatusEnum;

class UpdateTaskStatusServiceDto
{
    public function __construct(
        public int $taskId,
        public TaskStatusEnum $status,
        public int $userId,
    ) {}
}
