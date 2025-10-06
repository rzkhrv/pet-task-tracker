<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repository\TaskNotification\CreateTaskNotificationDto;
use App\Exceptions\Repository\TaskNotification\FailedWhenCreateTaskNotificationException;
use App\Models\TaskNotification;
use Throwable;

class TaskNotificationRepository
{
    /**
     * @throws FailedWhenCreateTaskNotificationException
     */
    public function create(CreateTaskNotificationDto $dto): TaskNotification
    {
        $taskNotification = new TaskNotification;

        $taskNotification->task_id = $dto->taskId;
        $taskNotification->user_id = $dto->userId;
        $taskNotification->message = $dto->message;

        try {
            $taskNotification->saveOrFail();
        } catch (Throwable $e) {
            throw new FailedWhenCreateTaskNotificationException(previous: $e);
        }

        return $taskNotification->refresh();
    }
}
