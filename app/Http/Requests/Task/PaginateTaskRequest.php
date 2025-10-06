<?php

declare(strict_types=1);

namespace App\Http\Requests\Task;

use App\Http\Requests\Common\PaginationRequest;
use App\Http\Requests\Task\Nested\TaskFiltersRequest;
use Kr0lik\DtoToSwagger\Attribute\Nested;
use Kr0lik\DtoToSwagger\Contract\QueryRequestInterface;
use Spatie\LaravelData\Data;

final class PaginateTaskRequest extends Data implements QueryRequestInterface
{
    public function __construct(
        #[Nested]
        public readonly ?TaskFiltersRequest $filters,

        #[Nested]
        public readonly ?PaginationRequest $pagination,
    ) {}
}
