<?php

declare(strict_types=1);

namespace App\Http\Responses\Task;

use App\Dto\Entity\TaskEntity;
use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use Kr0lik\DtoToSwagger\Contract\JsonResponseInterface;
use Spatie\LaravelData\Data;

class TaskResourceResponse extends Data implements JsonResponseInterface
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $title,
        public TaskStatusEnum $status,
        public TaskPriorityEnum $priority,
        public ?string $description = null,
        public string $createdAt,
    ) {}

    public static function fromEntity(TaskEntity $entity): self
    {
        return new self(
            id: $entity->id,
            userId: $entity->userId,
            title: $entity->title,
            status: $entity->status,
            priority: $entity->priority,
            description: $entity->description,
            createdAt: $entity->createdAt,
        );
    }
}
