<?php

declare(strict_types=1);

namespace App\Dto\Entity;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Models\Task;

class TaskWithCommentsAndUserEntity
{
    /**
     * @param  array<array-key, TaskCommentEntity>  $comments
     */
    public function __construct(
        public int $id,
        public UserEntity $user,
        public string $title,
        public TaskStatusEnum $status,
        public TaskPriorityEnum $priority,
        public array $comments,
        public ?string $description,
    ) {}

    public static function fromModel(Task $model): self
    {
        /** @var array<array-key, TaskCommentEntity> $comments */
        $comments = $model->comments
            ->map(fn ($comment) => TaskCommentEntity::fromModel($comment))
            ->toArray();

        return new self(
            id: $model->id,
            user: UserEntity::fromModel($model->user),
            title: $model->title,
            status: $model->status,
            priority: $model->priority,
            comments: $comments,
            description: $model->description,
        );
    }
}
