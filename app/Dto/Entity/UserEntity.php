<?php

declare(strict_types=1);

namespace App\Dto\Entity;

use App\Enum\UserPositionEnum;
use App\Models\User;

class UserEntity
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public UserPositionEnum $position,
    ) {}

    public static function fromModel(User $model): self
    {
        return new self(
            id: $model->id,
            name: $model->name,
            email: $model->email,
            position: $model->position,
        );
    }
}
