<?php

declare(strict_types=1);

namespace App\Http\Responses\Common;

final class MetaResponse
{
    public function __construct(
        public int $currentPage,
        public int $perPage,
        public int $total,
    ) {}
}
