<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Dto\Service\Task\StoreTaskCommentServiceDto;
use App\Enum\TaskStatusEnum;
use App\Events\TaskUpdatedEvent;
use App\Services\TaskService;
use App\Services\UserService;

class CreateCommentForCompletedTaskListener
{
    public function __construct(
        private TaskService $taskService,
        private UserService $userService,
    ) {}

    public function handle(TaskUpdatedEvent $event): void
    {
        $task = $event->model;

        if ($task->status === TaskStatusEnum::Completed) {
            $user = $this->userService->get($task->user_id);

            $this->taskService->storeComment(
                new StoreTaskCommentServiceDto(
                    taskId: $task->id,
                    comment: 'Task completed by '.$user->name,
                    userId: $task->user_id,
                )
            );
        }
    }
}
