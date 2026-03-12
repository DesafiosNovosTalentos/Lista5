<?php

namespace App\Domain\NotificationLog\Enum;

enum NotificationEnum: int
{
    case SENT = 1;
    case FAILED = 2;
}
