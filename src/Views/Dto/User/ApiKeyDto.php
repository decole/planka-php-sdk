<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class ApiKeyDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly ?string $apiKey = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
