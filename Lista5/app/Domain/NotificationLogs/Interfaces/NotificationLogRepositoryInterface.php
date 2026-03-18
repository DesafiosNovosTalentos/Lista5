<?php

namespace App\Domain\NotificationLogs\Interfaces;

use App\Domain\NotificationLogs\Entity\NotificationLog;

interface NotificationLogRepositoryInterface
{
    public function save(NotificationLog $dto): NotificationLog;

    public function findByUserId(string $user_id): array;

    public function findFailed(): array;
}
