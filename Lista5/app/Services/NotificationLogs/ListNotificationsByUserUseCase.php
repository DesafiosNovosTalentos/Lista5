<?php

namespace App\Services\NotificationLogs;

use App\Domain\NotificationLogs\Interfaces\NotificationLogRepositoryInterface;

class ListNotificationsByUserUseCase
{
    public function __construct(
        private NotificationLogRepositoryInterface $repository
    ) {}

    public function execute(string $userId): array
    {
        return $this->repository->findByUserId($userId);
    }
}
