<?php

declare(strict_types=1);

namespace App\Dto\Entity;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Models\Task;

class TaskEntity
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $title,
        public TaskStatusEnum $status,
        public TaskPriorityEnum $priority,
        public ?string $description,
    ) {}

    public static function fromModel(Task $model): self
    {
        return new self(
            id: $model->id,
            userId: $model->id,
            title: $model->title,
            status: $model->status,
            priority: $model->priority,
            description: $model->description,
        );
    }
}
