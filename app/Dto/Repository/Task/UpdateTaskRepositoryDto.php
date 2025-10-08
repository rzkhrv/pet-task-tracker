<?php

declare(strict_types=1);

namespace App\Dto\Repository\Task;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;

class UpdateTaskRepositoryDto
{
    public function __construct(
        public int $taskId,
        public ?string $title = null,
        public ?int $userId = null,
        public ?TaskPriorityEnum $priority = null,
        public ?TaskStatusEnum $status = null,
        public ?string $description = null,
    ) {}
}
