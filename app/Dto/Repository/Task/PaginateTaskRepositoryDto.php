<?php

declare(strict_types=1);

namespace App\Dto\Repository\Task;

use App\Dto\Repository\Common\PaginationDto;
use App\Dto\Repository\Task\Nested\TaskFiltersDto;

class PaginateTaskRepositoryDto
{
    public function __construct(
        public TaskFiltersDto $filters,

        public PaginationDto $pagination,
    ) {}
}
