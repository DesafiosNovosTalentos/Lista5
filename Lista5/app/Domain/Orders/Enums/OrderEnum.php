<?php

namespace App\Domain\Orders\Enums;

enum OrderEnum: int
{
    case PENDING = 1;
    case PROCESSING = 2;
    case COMPLETED = 3;
}
