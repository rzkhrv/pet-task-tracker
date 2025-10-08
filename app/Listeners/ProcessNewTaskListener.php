<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enum\TaskNotificationTypeEnum;
use App\Enum\TaskPriorityEnum;
use App\Events\TaskCreatedEvent;
use App\Jobs\SendTaskNotificationJob;

class ProcessNewTaskListener
{
    /**
     * Handle the event.
     */
    public function handle(TaskCreatedEvent $event): void
    {
        if ($event->model->priority === TaskPriorityEnum::High) {
            SendTaskNotificationJob::dispatch(
                taskId: $event->model->id,
                notificationType: TaskNotificationTypeEnum::TaskAssigned,
            );
        }
    }
}
