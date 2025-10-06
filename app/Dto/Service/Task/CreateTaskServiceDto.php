<?php

declare(strict_types=1);

namespace App\Dto\Service\Task;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;

class CreateTaskServiceDto
{
    public function __construct(
        public string $title,
        public TaskPriorityEnum $priority,
        public ?int $userId,
        public ?TaskStatusEnum $status = null,
        public ?string $description = null,
    ) {}
}
