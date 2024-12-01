<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Background;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;

class BackgroundDto implements OutputDtoInterface
{
    public function __construct(
        public BackgroundTypeEnum $type,
        public ?BackgroundGradientEnum $name,
    ) {}
}
