<?php

namespace App\Domain\NotificationLogs\Interfaces;

use App\Domain\NotificationLogs\Dto\CreateNotificationLogDTO;
use App\Domain\NotificationLogs\Entity\NotificationLog;

interface NotificationLogRepositoryInterface
{
    public function save(CreateNotificationLogDTO $dto): NotificationLog;
    public function findByUserId(string $userId): array;
    public function findFailed(): array;
}
