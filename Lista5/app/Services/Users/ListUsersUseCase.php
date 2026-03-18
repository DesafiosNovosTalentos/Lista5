<?php

namespace App\Services\Users;

use App\Domain\Users\Interfaces\UserRepositoryInterface;

class ListUsersUseCase
{
    public function __construct(private UserRepositoryInterface $user_repository) {}

    public function execute(): array
    {
        return $this->user_repository->findAll();
    }
}
