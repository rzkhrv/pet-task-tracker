<?php

declare(strict_types=1);

namespace App\Enum;

enum UserPositionEnum: string
{
    case Manager = 'manager';
    case Developer = 'developer';
    case Tester = 'tester';
}
