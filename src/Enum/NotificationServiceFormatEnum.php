<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum NotificationServiceFormatEnum: string
{
    case TEXT = 'text';
    case MARKDOWN = 'markdown';
    case HTML = 'html';
}
