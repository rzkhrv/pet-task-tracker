<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Repository\Task\Nested\TaskFiltersDto;
use App\Dto\Repository\Task\PaginateTaskRepositoryDto;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

final class TaskRepository
{
    /**
     * @return LengthAwarePaginator<array-key, Task>
     */
    public function paginate(PaginateTaskRepositoryDto $dto): LengthAwarePaginator
    {
        $query = Task::query();

        $this->applyFilters(query: $query, dto: $dto->filters);

        return $query
            ->orderByDesc('created_at')
            ->paginate(
                perPage: $dto->pagination->limit,
                page: $dto->pagination->page
            );
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
}
