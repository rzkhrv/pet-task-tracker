<?php

declare(strict_types=1);

namespace App\Dto\Repository\Task;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;

class CreateTaskRepositoryDto
{
    public function __construct(
        public string $title,
        public int $userId,
        public TaskPriorityEnum $priority,
        public TaskStatusEnum $status,
        public ?string $description = null,
    ) {}
}
