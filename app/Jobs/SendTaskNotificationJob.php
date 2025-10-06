<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Dto\Service\TaskNotification\ProcessTaskNotificationDto;
use App\Enum\TaskNotificationTypeEnum;
use App\Services\TaskNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendTaskNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $taskId,
        public TaskNotificationTypeEnum $notificationType,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(TaskNotificationService $taskNotificationService): void
    {
        $taskNotificationService->process(
            new ProcessTaskNotificationDto(
                taskId: $this->taskId,
                type: $this->notificationType
            )
        );
    }
}
