<?php

declare(strict_types=1);

namespace Planka\Bridge\Enum;

enum BoardDefaultCardTypeEnum: string
{
    case PROJECT = 'project';
    case STORY = 'story';
}
