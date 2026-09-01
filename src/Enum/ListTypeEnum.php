<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum ListTypeEnum: string
{
    case ACTIVE = 'active';
    case CLOSED = 'closed';
    case ARCHIVE = 'archive';
    case TRASH = 'trash';
}
