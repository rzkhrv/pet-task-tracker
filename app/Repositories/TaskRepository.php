<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Entity\TaskEntity;
use App\Dto\Entity\TaskWithCommentsAndUserEntity;
use App\Dto\Repository\Task\CreateTaskRepositoryDto;
use App\Dto\Repository\Task\Nested\TaskFiltersDto;
use App\Dto\Repository\Task\PaginateTaskRepositoryDto;
use App\Dto\Repository\Task\UpdateTaskRepositoryDto;
use App\Enum\TaskStatusEnum;
use App\Exceptions\Repository\Task\FailedWhenCreateTaskRepositoryException;
use App\Exceptions\Repository\Task\TaskNotFoundRepositoryException;
use App\Exceptions\Repository\UnexpectedRepositoryException;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Throwable;

final class TaskRepository
{
    public function __construct(
        private int $overdueDays,
    ) {}

    /**
     * @return LengthAwarePaginator<array-key, TaskEntity>
     */
    public function paginate(PaginateTaskRepositoryDto $dto): LengthAwarePaginator
    {
        $query = Task::query();

        $this->applyFilters(query: $query, dto: $dto->filters);

        $paginator = $query
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $dto->pagination->limit,
                page: $dto->pagination->page
            );

        return new LengthAwarePaginator(
            items: $paginator->getCollection()->map(static function (Task $task): TaskEntity {
                return TaskEntity::fromModel($task);
            }),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
            options: $paginator->getOptions(),
        );
    }

    /**
     * @throws FailedWhenCreateTaskRepositoryException
     */
    public function create(CreateTaskRepositoryDto $dto): TaskEntity
    {
        $task = new Task;

        $task->title = $dto->title;
        $task->description = $dto->description;
        $task->status = $dto->status;
        $task->priority = $dto->priority;
        $task->user_id = $dto->userId;

        try {
            $task->saveOrFail();
        } catch (Throwable $e) {
            throw new FailedWhenCreateTaskRepositoryException(previous: $e);
        }

        return TaskEntity::fromModel($task->refresh());
    }

    /**
     * @throws UnexpectedRepositoryException
     * @throws TaskNotFoundRepositoryException
     */
    public function get(int $taskId): TaskEntity
    {
        $task = $this->getAsModel($taskId);

        return TaskEntity::fromModel($task);
    }

    /**
     * @throws UnexpectedRepositoryException
     * @throws TaskNotFoundRepositoryException
     */
    public function update(UpdateTaskRepositoryDto $dto): TaskEntity
    {
        $task = $this->getAsModel($dto->taskId);

        $task->fill(
            $this->makeFieldsForUpdate($dto)
        );

        try {
            $task->saveOrFail();
        } catch (Throwable $e) {
            throw new UnexpectedRepositoryException(previous: $e);
        }

        return TaskEntity::fromModel($task);
    }

    /**
     * @throws UnexpectedRepositoryException
     * @throws TaskNotFoundRepositoryException
     */
    public function getWithCommentsAndUser(int $taskId): TaskWithCommentsAndUserEntity
    {
        $task = $this->getAsModel($taskId);

        $task->loadMissing(['comments', 'user']);

        return TaskWithCommentsAndUserEntity::fromModel($task);
    }

    /**
     * @return array<int>
     */
    public function getAllOverdueIds(): array
    {
        /** @var array<int> $ids */
        $ids = Task::query()
            ->where('created_at', '>', now()->subDays($this->overdueDays))
            ->where('status', TaskStatusEnum::InProgress)
            ->pluck('id')
            ->toArray();

        return $ids;
    }

    /**
     * @throws UnexpectedRepositoryException
     * @throws TaskNotFoundRepositoryException
     */
    private function getAsModel(int $taskId): Task
    {
        try {
            return Task::query()
                ->findOrFail($taskId);
        } catch (ModelNotFoundException) {
            throw new TaskNotFoundRepositoryException('Task not found');
        } catch (Throwable $e) {
            throw new UnexpectedRepositoryException(previous: $e);
        }
    }

    /**
     * @param  Builder<Task>  $query
     */
    private function applyFilters(Builder $query, TaskFiltersDto $dto): void
    {
        if ($dto->status !== null) {
            $query->where('status', $dto->status);
        }

        if ($dto->priority !== null) {
            $query->where('priority', $dto->priority);
        }

        if ($dto->userId !== null) {
            $query->where('user_id', $dto->priority);
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function makeFieldsForUpdate(UpdateTaskRepositoryDto $dto): array
    {
        $updates = [
            'title' => $dto->description,
            'status' => $dto->status,
            'priority' => $dto->priority,
            'user_id' => $dto->userId,
        ];

        $updates = array_filter($updates, static fn ($item) => $item !== null);

        $updates['description'] = $dto->description;

        return $updates;
    }
}
