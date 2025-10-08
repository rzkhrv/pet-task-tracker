<?php

declare(strict_types=1);

namespace App\Http\Responses\Task;

use App\Dto\Entity\TaskCommentEntity;
use App\Dto\Entity\TaskWithCommentsAndUserEntity;
use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Http\Responses\Task\Nested\ShortUserInfoResourceResponse;

class TaskWithCommentsAndUserResourceResponse
{
    /**
     * @param  array<array-key, TaskCommentResourceResponse>  $comments
     */
    public function __construct(
        public int $id,
        public ShortUserInfoResourceResponse $user,
        public string $title,
        public TaskStatusEnum $status,
        public TaskPriorityEnum $priority,
        public array $comments,
        public ?string $description = null,
    ) {}

    public static function fromEntity(TaskWithCommentsAndUserEntity $entity): self
    {
        $comments = array_map(static function (TaskCommentEntity $comment): TaskCommentResourceResponse {
            return TaskCommentResourceResponse::fromEntity($comment);
        }, $entity->comments);

        return new self(
            id: $entity->id,
            user: new ShortUserInfoResourceResponse(
                name: $entity->user->name,
                position: $entity->user->position,
            ),
            title: $entity->title,
            status: $entity->status,
            priority: $entity->priority,
            comments: $comments,
            description: $entity->description,
        );
    }
}
