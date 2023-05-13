<?php

namespace Planka\Bridge\Enum;

enum BoardMembershipRoleEnum: string
{
    case EDITOR = 'editor';
    case VIEWER = 'viewer';
}
