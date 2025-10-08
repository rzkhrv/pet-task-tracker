<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\Entity\UserEntity;
use App\Enum\UserPositionEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function findById(int $userId): ?UserEntity
    {
        $user = User::find($userId);

        if ($user === null) {
            return null;
        }

        return UserEntity::fromModel($user);
    }

    public function findByPosition(UserPositionEnum $position): ?UserEntity
    {
        $user = $this->buildByPositionQuery($position)
            ->first();

        if ($user === null) {
            return null;
        }

        return UserEntity::fromModel($user);
    }

    /**
     * @return array<array-key, int>
     */
    public function getIdsByPosition(UserPositionEnum $position): array
    {
        /** @var array<int> $ids */
        $ids = $this->buildByPositionQuery($position)
            ->pluck('id')
            ->toArray();

        return $ids;
    }

    /**
     * @return Builder<User>
     */
    protected function buildByPositionQuery(UserPositionEnum $position): Builder
    {
        return User::query()->where('position', $position);
    }
}
