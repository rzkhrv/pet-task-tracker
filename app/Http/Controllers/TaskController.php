<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Task\PaginateTaskRequest;
use App\Http\Requests\Task\StoreTaskCommentRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Responses\Task\PaginatedTaskCollectionResponse;
use App\Http\Responses\Task\TaskCommentResourceResponse;
use App\Http\Responses\Task\TaskResourceResponse;
use App\Http\Responses\Task\TaskWithCommentsAndUserResourceResponse;
use App\Http\Services\TaskHttpService;

final class TaskController
{
    public function __construct(
        private TaskHttpService $taskHttpService,
    ) {}

    public function paginate(PaginateTaskRequest $request): PaginatedTaskCollectionResponse
    {
        return $this->taskHttpService->paginate($request);
    }

    public function store(StoreTaskRequest $request): TaskResourceResponse
    {
        return $this->taskHttpService->store($request);
    }

    public function updateStatus(UpdateTaskStatusRequest $request): TaskResourceResponse
    {
        return $this->taskHttpService->updateTaskStatus($request);
    }

    public function storeComment(StoreTaskCommentRequest $request): TaskCommentResourceResponse
    {
        return $this->taskHttpService->storeTaskComment($request);
    }

    public function show(int $id): TaskWithCommentsAndUserResourceResponse
    {
        return $this->taskHttpService->getWithCommentsAndUser($id);
    }
}
