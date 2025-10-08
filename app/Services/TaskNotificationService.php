<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Repository\TaskNotification\CreateTaskNotificationDto;
use App\Dto\Service\TaskNotification\ProcessTaskNotificationDto;
use App\Enum\TaskNotificationTypeEnum;
use App\Enum\UserPositionEnum;
use App\Exceptions\Repository\TaskNotification\FailedWhenCreateTaskNotificationRepositoryException;
use App\Models\TaskNotification;
use App\Repositories\TaskNotificationRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class TaskNotificationService
{
    public function __construct(
        private UserRepository $userRepository,
        private TaskNotificationRepository $taskNotificationRepository,
    ) {}

    public function process(ProcessTaskNotificationDto $dto): void
    {
        $storedNotifications = $this->storeNotifications($dto);

        $this->sendNotifications($storedNotifications);
    }

    /**
     * @return array<array-key, TaskNotification>
     *
     * @throws Throwable
     * @throws FailedWhenCreateTaskNotificationRepositoryException
     */
    protected function storeNotifications(ProcessTaskNotificationDto $dto): array
    {
        $managerIds = $this->userRepository->getIdsByPosition(UserPositionEnum::Manager);

        $taskNotifications = [];

        DB::beginTransaction();

        foreach ($managerIds as $managerId) {
            try {
                $taskNotifications[] = $this->taskNotificationRepository->create(
                    new CreateTaskNotificationDto(
                        taskId: $dto->taskId,
                        userId: $managerId,
                        message: $this->makeMessageForNotification($dto)
                    )
                );
            } catch (Throwable $e) {
                DB::rollBack();
                throw $e;
            }
        }

        DB::commit();

        return $taskNotifications;
    }

    protected function makeMessageForNotification(ProcessTaskNotificationDto $dto): string
    {
        return match ($dto->type) {
            TaskNotificationTypeEnum::TaskAssigned => 'New Task Assigned',
            TaskNotificationTypeEnum::StatusChanged => 'Task Status Changed',
            TaskNotificationTypeEnum::Overdue => 'Task Overdue',
        };
    }

    /**
     * @param  array<array-key, TaskNotification>  $notifications
     */
    protected function sendNotifications(array $notifications): void
    {
        foreach ($notifications as $notification) {
            Log::info('simulation '.$notification->id);
        }
    }
}
