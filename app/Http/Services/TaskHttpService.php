<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\Service\Task\CreateTaskServiceDto;
use App\Dto\Service\Task\PaginateTaskServiceDto;
use App\Http\Requests\Common\PaginationRequest;
use App\Http\Requests\Task\Nested\TaskFiltersRequest;
use App\Http\Requests\Task\PaginateTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Responses\Task\PaginatedTaskCollectionResponse;
use App\Http\Responses\Task\TaskResourceResponse;
use App\Services\TaskService;

final class TaskHttpService
{
    public function __construct(
        private TaskService $taskService,
    ) {}

    public function paginate(PaginateTaskRequest $request): PaginatedTaskCollectionResponse
    {
        $paginator = $this->taskService->paginate(
            new PaginateTaskServiceDto(
                filters: $request->filters ?? TaskFiltersRequest::createEmpty(),
                pagination: $request->pagination ?? PaginationRequest::createEmpty()
            )
        );

        return PaginatedTaskCollectionResponse::fromPaginator($paginator);
    }

    public function store(StoreTaskRequest $request): TaskResourceResponse
    {
        $task = $this->taskService->create(
            new CreateTaskServiceDto(
                title: $request->title,
                priority: $request->priority,
                userId: $request->userId,
                description: $request->description,
            )
        );

        return TaskResourceResponse::fromModel($task);
    }
}
