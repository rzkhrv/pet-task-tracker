<?php

declare(strict_types=1);

namespace App\Dto\Repository\TaskComment;

class CreateTaskCommentRepositoryDto
{
    public function __construct(
        public int $taskId,
        public string $comment,
        public int $userId,
    ) {}
}
