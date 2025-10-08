<?php

declare(strict_types=1);

namespace App\Dto\Service\Task;

class StoreTaskCommentServiceDto
{
    public function __construct(
        public int $taskId,
        public string $comment,
        public int $userId,
    ) {}
}
