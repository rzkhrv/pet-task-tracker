<?php

declare(strict_types=1);

namespace App\Http\Responses\Task;

use App\Dto\Entity\TaskCommentEntity;
use Kr0lik\DtoToSwagger\Contract\JsonResponseInterface;
use Spatie\LaravelData\Data;

class TaskCommentResourceResponse extends Data implements JsonResponseInterface
{
    public function __construct(
        public int $id,
        public int $taskId,
        public string $comment,
        public int $userId,
        public string $createdAt,
    ) {}

    public static function fromEntity(TaskCommentEntity $entity): self
    {
        return new self(
            id: $entity->id,
            taskId: $entity->taskId,
            comment: $entity->comment,
            userId: $entity->userId,
            createdAt: $entity->createdAt,
        );
    }
}
