<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Enum\TaskStatusEnum;
use Kr0lik\DtoToSwagger\Contract\JsonRequestInterface;
use OpenApi\Attributes\Parameter;
use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Data;

class UpdateTaskStatusRequest extends Data implements JsonRequestInterface
{
    public function __construct(
        #[FromRouteParameter('id', false)]
        #[Parameter(in: 'path')]
        public int $taskId,

        public TaskStatusEnum $status,
        public int $user_id,
    ) {}
}
