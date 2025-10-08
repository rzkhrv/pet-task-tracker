<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Events\TaskCreatingEvent;

class OverrideHighPriorityTaskStatusListener
{
    public function handle(TaskCreatingEvent $event): void
    {
        if ($event->model->priority === TaskPriorityEnum::High) {
            $event->model->status = TaskStatusEnum::InProgress;
        }
    }
}
