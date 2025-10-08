<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Enum\TaskNotificationTypeEnum;
use App\Events\TaskUpdatedEvent;
use App\Jobs\SendTaskNotificationJob;

class SendNotificationWhenStatusChangedListener
{
    public function handle(TaskUpdatedEvent $event): void
    {
        $isStatusChanged = $event->model->wasChanged('status');

        if ($isStatusChanged) {
            SendTaskNotificationJob::dispatch(
                taskId: $event->model->id,
                notificationType: TaskNotificationTypeEnum::StatusChanged,
            );
        }
    }
}
