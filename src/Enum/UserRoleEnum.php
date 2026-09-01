<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case PROJECT_OWNER = 'projectOwner';
    case BOARD_USER = 'boardUser';
}
