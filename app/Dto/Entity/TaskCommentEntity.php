<?php

declare(strict_types=1);

namespace App\Dto\Entity;

use App\Models\TaskComment;

class TaskCommentEntity
{
    public function __construct(
        public int $id,
        public int $userId,
        public int $taskId,
        public string $comment,
        public string $createdAt,
    ) {}

    public static function fromModel(TaskComment $model): self
    {
        return new self(
            id: $model->id,
            userId: $model->user_id,
            taskId: $model->task_id,
            comment: $model->comment,
            createdAt: $model->created_at->toDateTimeString(),
        );
    }
}
