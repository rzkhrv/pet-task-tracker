<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Entity\TaskCommentEntity;
use App\Dto\Repository\TaskComment\CreateTaskCommentRepositoryDto;
use App\Exceptions\Repository\TaskComment\FailedWhenCreateTaskCommentRepositoryException;
use App\Models\TaskComment;
use Throwable;

class TaskCommentRepository
{
    /**
     * @throws FailedWhenCreateTaskCommentRepositoryException
     */
    public function create(CreateTaskCommentRepositoryDto $dto): TaskCommentEntity
    {
        $taskComment = new TaskComment;

        $taskComment->task_id = $dto->taskId;
        $taskComment->comment = $dto->comment;
        $taskComment->user_id = $dto->userId;

        try {
            $taskComment->saveOrFail();
        } catch (Throwable $e) {
            throw new FailedWhenCreateTaskCommentRepositoryException(previous: $e);
        }

        return TaskCommentEntity::fromModel($taskComment->refresh());
    }
}
