<?php

namespace App\Domain\Users\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case CLIENT = 'client';
}
