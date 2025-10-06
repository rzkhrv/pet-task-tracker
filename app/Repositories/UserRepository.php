<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enum\UserPositionEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function findById(int $userId): ?User
    {
        return User::find($userId);
    }

    public function findByPosition(UserPositionEnum $position): ?User
    {
        return $this->buildByPositionQuery($position)
            ->first();
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
