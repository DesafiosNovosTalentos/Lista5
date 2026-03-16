<?php

namespace App\Domain\NotificationLogs\Dto;

use App\Domain\NotificationLogs\Enum\NotificationEnum;

class CreateNotificationLogDTO
{
    public function __construct(
        public readonly string $userId,
        public readonly string $orderId,
        public readonly string $message,
        public readonly NotificationEnum $status,
        public readonly int $attempts,
    ) {}
}
