<?php

declare(strict_types=1);

namespace App\Dto\Repository\Common;

use Spatie\LaravelData\Data;

class PaginationDto extends Data
{
    public function __construct(
        public int $page = 1,
        public int $limit = 10,
    ) {}

    public static function createEmpty(): self
    {
        return new self;
    }
}
