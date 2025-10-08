<?php

declare(strict_types=1);

namespace App\Http\Services;

use App\Dto\Service\Task\CreateTaskServiceDto;
use App\Dto\Service\Task\PaginateTaskServiceDto;
use App\Dto\Service\Task\StoreTaskCommentServiceDto;
use App\Dto\Service\Task\UpdateTaskStatusServiceDto;
use App\Http\Requests\Common\PaginationRequest;
use App\Http\Requests\Task\Nested\TaskFiltersRequest;
use App\Http\Requests\Task\PaginateTaskRequest;
use App\Http\Requests\Task\StoreTaskCommentRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Responses\Task\PaginatedTaskCollectionResponse;
use App\Http\Responses\Task\TaskCommentResourceResponse;
use App\Http\Responses\Task\TaskResourceResponse;
use App\Http\Responses\Task\TaskWithCommentsAndUserResourceResponse;
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
                userId: $request->user_id,
                description: $request->description,
            )
        );

        return TaskResourceResponse::fromEntity($task);
    }

    public function updateTaskStatus(UpdateTaskStatusRequest $request): TaskResourceResponse
    {
        $task = $this->taskService->updateTaskStatus(
            new UpdateTaskStatusServiceDto(
                taskId: $request->taskId,
                status: $request->status,
                userId: $request->user_id,
            )
        );

        return TaskResourceResponse::fromEntity($task);
    }

    public function storeTaskComment(StoreTaskCommentRequest $request): TaskCommentResourceResponse
    {
        $comment = $this->taskService->storeComment(
            new StoreTaskCommentServiceDto(
                taskId: $request->taskId,
                comment: $request->comment,
                userId: $request->user_id,
            )
        );

        return TaskCommentResourceResponse::fromEntity($comment);
    }

    public function getWithCommentsAndUser(int $taskId): TaskWithCommentsAndUserResourceResponse
    {
        $taskWithComments = $this->taskService->getWithCommentsAndUser($taskId);

        return TaskWithCommentsAndUserResourceResponse::fromEntity($taskWithComments);
    }
}
