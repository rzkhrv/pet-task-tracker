<?php

declare(strict_types=1);

namespace App\Http\Responses\Task;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use App\Models\Task;
use OpenApi\Attributes\Response;
use Spatie\LaravelData\Data;

#[Response(response: 200, description: 'basic-response')]
final class TaskResourceResponse extends Data
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $title,
        public TaskStatusEnum $status,
        public TaskPriorityEnum $priority,
        public ?string $description = null,
    ) {}

    public static function fromModel(Task $task): self
    {
        return new self(
            id: $task->id,
            userId: $task->user_id,
            title: $task->title,
            status: $task->status,
            priority: $task->priority,
            description: $task->description,
        );
    }
}
