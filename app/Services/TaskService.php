<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Entity\TaskCommentEntity;
use App\Dto\Entity\TaskEntity;
use App\Dto\Entity\TaskWithCommentsAndUserEntity;
use App\Dto\Entity\UserEntity;
use App\Dto\Repository\Task\CreateTaskRepositoryDto;
use App\Dto\Repository\Task\PaginateTaskRepositoryDto;
use App\Dto\Repository\Task\UpdateTaskRepositoryDto;
use App\Dto\Repository\TaskComment\CreateTaskCommentRepositoryDto;
use App\Dto\Service\Task\CreateTaskServiceDto;
use App\Dto\Service\Task\PaginateTaskServiceDto;
use App\Dto\Service\Task\StoreTaskCommentServiceDto;
use App\Dto\Service\Task\UpdateTaskStatusServiceDto;
use App\Enum\TaskStatusEnum;
use App\Enum\UserPositionEnum;
use App\Exceptions\Repository\Task\TaskNotFoundRepositoryException;
use App\Exceptions\Repository\TaskComment\FailedWhenCreateTaskCommentRepositoryException;
use App\Exceptions\Repository\UnexpectedRepositoryException;
use App\Exceptions\Service\Task\CanNotUpdateTaskStatusServiceException;
use App\Exceptions\Service\Task\CreateTaskServiceException;
use App\Exceptions\Service\Task\TaskNotFoundServiceException;
use App\Exceptions\Service\TaskComment\CreateTaskCommentServiceException;
use App\Exceptions\Service\UnexpectedServiceException;
use App\Exceptions\Service\User\UserNotFoundException;
use App\Repositories\TaskCommentRepository;
use App\Repositories\TaskRepository;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

final class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
        private UserRepository $userRepository,
        private TaskCommentRepository $taskCommentRepository,
    ) {}

    /**
     * @return LengthAwarePaginator<array-key, TaskEntity>
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
     * @throws CreateTaskServiceException
     */
    public function create(CreateTaskServiceDto $dto): TaskEntity
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
            throw new CreateTaskServiceException(message: 'Failed to create task', previous: $e);
        }

        return $task;
    }

    /**
     * @throws CanNotUpdateTaskStatusServiceException
     * @throws UnexpectedServiceException
     * @throws UserNotFoundException
     * @throws TaskNotFoundServiceException
     */
    public function updateTaskStatus(UpdateTaskStatusServiceDto $dto): TaskEntity
    {
        $this->checkUserExists($dto->userId);

        try {
            $updatedTask = $this->taskRepository->update(
                new UpdateTaskRepositoryDto(
                    taskId: $dto->taskId,
                    userId: $dto->userId,
                    status: $dto->status,
                )
            );
        } catch (UnexpectedRepositoryException $e) {
            throw new UnexpectedServiceException(previous: $e);
        } catch (TaskNotFoundRepositoryException) {
            throw new TaskNotFoundServiceException('Task not found');
        } catch (Throwable $e) {
            throw new CanNotUpdateTaskStatusServiceException(previous: $e);
        }

        return $updatedTask;
    }

    /**
     * @throws CreateTaskCommentServiceException
     * @throws TaskNotFoundServiceException
     * @throws UnexpectedServiceException
     */
    public function storeComment(StoreTaskCommentServiceDto $dto): TaskCommentEntity
    {
        $task = $this->get($dto->taskId);

        try {
            $taskComment = $this->taskCommentRepository->create(
                new CreateTaskCommentRepositoryDto(
                    taskId: $task->id,
                    comment: $dto->comment,
                    userId: $dto->userId,
                )
            );
        } catch (FailedWhenCreateTaskCommentRepositoryException $e) {
            throw new CreateTaskCommentServiceException(message: 'Failed to create task comment', previous: $e);
        }

        return $taskComment;
    }

    /**
     * @throws TaskNotFoundServiceException
     * @throws UnexpectedServiceException
     */
    public function get(int $taskId): TaskEntity
    {
        try {
            return $this->taskRepository->get($taskId);
        } catch (UnexpectedRepositoryException $e) {
            throw new UnexpectedServiceException(previous: $e);
        } catch (TaskNotFoundRepositoryException) {
            throw new TaskNotFoundServiceException('Task not found');
        }
    }

    /**
     * @throws TaskNotFoundServiceException
     * @throws UnexpectedServiceException
     */
    public function getWithCommentsAndUser(int $taskId): TaskWithCommentsAndUserEntity
    {
        try {
            return $this->taskRepository->getWithCommentsAndUser($taskId);
        } catch (UnexpectedRepositoryException $e) {
            throw new UnexpectedServiceException(previous: $e);
        } catch (TaskNotFoundRepositoryException) {
            throw new TaskNotFoundServiceException('Task not found');
        }
    }

    /**
     * @return array<int>
     */
    public function getAllOverdueIds(): array
    {
        return $this->taskRepository->getAllOverdueIds();
    }

    public function storeOverdueComment(int $taskId): void
    {
        $task = $this->get($taskId);

        $this->storeComment(
            new StoreTaskCommentServiceDto(
                taskId: $task->id,
                comment: 'Task is overdue! Created '.$task->createdAt,
                userId: $task->userId
            )
        );
    }

    /**
     * @throws CreateTaskServiceException
     */
    private function getUserForNewTask(?int $userId): UserEntity
    {
        $user = $userId === null
            ? $this->userRepository->findByPosition(UserPositionEnum::Manager)
            : $this->userRepository->findById($userId);

        if ($user === null) {
            throw new CreateTaskServiceException(
                'Can not create new task, because user does not exist'
            );
        }

        return $user;
    }

    private function getDefaultTaskStatus(): TaskStatusEnum
    {
        return TaskStatusEnum::New;
    }

    /**
     * @throws UserNotFoundException
     */
    private function checkUserExists(int $userId): void
    {
        $isUserExists = $this->userRepository->findById($userId) !== null;

        if ($isUserExists === false) {
            throw new UserNotFoundException('User not found');
        }
    }
}
