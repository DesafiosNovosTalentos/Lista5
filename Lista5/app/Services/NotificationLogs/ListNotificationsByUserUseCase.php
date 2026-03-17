<?php

namespace App\Services\NotificationLogs;

use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;

class ListNotificationsByUserUseCase
{
    public function __construct(
        private NotificationLogRepositoryInterface $repository
    ) {}

    public function execute(string $user_id): array
    {
        return $this->repository->findByUserId($user_id);
    }
}
