<?php

namespace App\Domain\NotificationLogs\Enum;

enum NotificationEnum: int
{
    case SENT = 1;
    case FAILED = 2;
}
