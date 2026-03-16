<?php

namespace App\Domain\NotificationLogs\Dto;

use App\Domain\NotificationLogs\Enum\NotificationEnum;

readonly class CreateNotificationLogDTO
{
    public function __construct(
        public string $userId,
        public string $orderId,
        public string $message,
        public NotificationEnum $status,
        public int $attempts,
    ) {}
}
