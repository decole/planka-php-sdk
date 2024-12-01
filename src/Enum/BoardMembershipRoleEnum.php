<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum BoardMembershipRoleEnum: string
{
    case EDITOR = 'editor';
    case VIEWER = 'viewer';
}
