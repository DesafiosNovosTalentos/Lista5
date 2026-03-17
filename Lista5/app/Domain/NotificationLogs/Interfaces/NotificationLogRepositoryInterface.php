<?php

namespace App\Domain\NotificationLogs\Interfaces;

use App\Domain\NotificationLogs\Entity\NotificationLog;

interface NotificationLogRepositoryInterface
{
    public function save(NotificationLog $dto): NotificationLog;

    public function findByUserId(string $userId): array;

    public function findFailed(): array;

    public function findById(string $id): ?NotificationLog;

    public function update(NotificationLog $notification_log): NotificationLog;
}
