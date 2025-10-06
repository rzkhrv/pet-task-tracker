<?php

namespace App\Listeners;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Events\TaskCreatingEvent;

class OverrideHighPriorityTaskStatusListener
{
    public function handle(TaskCreatingEvent $event): void
    {
        if ($event->task->priority === TaskPriorityEnum::High) {
            $event->task->status = TaskStatusEnum::InProgress;
        }
    }
}
