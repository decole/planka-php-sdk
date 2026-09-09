<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\AccessToken;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class AccessTokenDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?string $token = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
