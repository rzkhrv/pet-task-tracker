<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Repository\Task\PaginateTaskRepositoryDto;
use App\Dto\Service\Task\PaginateTaskServiceDto;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Pagination\LengthAwarePaginator;

final class TaskService
{
    public function __construct(
        private TaskRepository $taskRepository,
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
}
