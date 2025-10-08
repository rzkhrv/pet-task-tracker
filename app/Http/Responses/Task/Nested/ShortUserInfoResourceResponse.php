<?php

declare(strict_types=1);

namespace App\Http\Responses\Task\Nested;

use App\Enum\UserPositionEnum;

class ShortUserInfoResourceResponse
{
    public function __construct(
        public string $name,
        public UserPositionEnum $position,
    ) {}
}
