<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum ProjectTypeEnum: string
{
    case PRIVATE = 'private';
    case SHARED = 'shared';
}
