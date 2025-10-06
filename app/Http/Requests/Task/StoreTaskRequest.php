<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Enum\TaskPriorityEnum;
use Kr0lik\DtoToSwagger\Contract\JsonRequestInterface;
use Spatie\LaravelData\Data;

class StoreTaskRequest extends Data implements JsonRequestInterface
{
    public function __construct(
        public string $title,
        public TaskPriorityEnum $priority,
        public ?int $userId,
        public ?string $description = null,
    ) {}
}
