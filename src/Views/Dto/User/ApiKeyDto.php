<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class ApiKeyDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?string $apiKey = null,
        public readonly array $_rawResponse = [],
    ) {}
}
