<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\Task\PaginateTaskRequest;
use App\Http\Responses\Task\PaginatedTaskCollectionResponse;
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
}
