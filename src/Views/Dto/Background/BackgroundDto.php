<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Background;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;

class BackgroundDto implements OutputDtoInterface
{
    public function __construct(
        public ?BackgroundTypeEnum $type = null,
        public ?BackgroundGradientEnum $gradient = null,
        public ?BackgroundGradientEnum $name = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
