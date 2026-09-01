<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum BoardDefaultViewEnum: string
{
    case KANBAN = 'kanban';
    case GRID = 'grid';
    case LIST = 'list';
}
