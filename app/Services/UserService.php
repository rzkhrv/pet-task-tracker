<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\Entity\UserEntity;
use App\Exceptions\Service\User\UserNotFoundException;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {}

    /**
     * @throws UserNotFoundException
     */
    public function get(int $userId): UserEntity
    {
        $user = $this->userRepository->findById($userId);

        if ($user === null) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}
