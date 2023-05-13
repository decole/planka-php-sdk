<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Background;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class BackgroundDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $type,
    ) {
    }
}