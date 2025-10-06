<?php

declare(strict_types=1);

namespace App\Dto\Repository\Task\Nested;

use App\Enum\TaskPriorityEnum;
use App\Enum\TaskStatusEnum;
use Spatie\LaravelData\Data;

class TaskFiltersDto extends Data
{
    public function __construct(
        public ?TaskStatusEnum $status = null,
        public ?TaskPriorityEnum $priority = null,
        public ?int $userId = null,
    ) {}

    public static function createEmpty(): self
    {
        return new self;
    }
}
