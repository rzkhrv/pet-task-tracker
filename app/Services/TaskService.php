<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Repository\Task\CreateTaskRepositoryDto;
use App\Dto\Repository\Task\PaginateTaskRepositoryDto;
use App\Dto\Service\Task\CreateTaskServiceDto;
use App\Dto\Service\Task\PaginateTaskServiceDto;
use App\Enum\TaskStatusEnum;
use App\Enum\UserPositionEnum;
use App\Exceptions\Service\Task\CreateTaskException;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

final class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private UserRepository $userRepository,
    ) {}

    /**
     * @return LengthAwarePaginator<array-key, Task>
     */
    public function paginate(PaginateTaskServiceDto $dto): LengthAwarePaginator
    {
        return $this->taskRepository->paginate(
            new PaginateTaskRepositoryDto(
                filters: $dto->filters,
                pagination: $dto->pagination
            )
        );
    }

    /**
     * @throws CreateTaskException
     */
    public function create(CreateTaskServiceDto $dto): Task
    {
        $user = $this->getUserForNewTask($dto->userId);

        try {
            $task = $this->taskRepository->create(
                new CreateTaskRepositoryDto(
                    title: $dto->title,
                    userId: $user->id,
                    priority: $dto->priority,
                    status: $dto->status ?? $this->getDefaultTaskStatus(),
                    description: $dto->description,
                )
            );
        } catch (Throwable $e) {
            throw new CreateTaskException(message: 'Failed to create task', previous: $e);
        }

        return $task;
    }

    /**
     * @throws CreateTaskException
     */
    private function getUserForNewTask(?int $userId): User
    {
        $user = $userId === null
            ? $this->userRepository->findByPosition(UserPositionEnum::Manager)
            : $this->userRepository->findById($userId);

        if ($user === null) {
            throw new CreateTaskException(
                'Can not create new task, because user does not exist'
            );
        }

        return $user;
    }

    private function getDefaultTaskStatus(): TaskStatusEnum
    {
        return TaskStatusEnum::New;
    }
}
