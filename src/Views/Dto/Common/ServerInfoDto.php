<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Common;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

final class ServerInfoDto implements OutputDtoInterface
{
    public function __construct(
        public readonly int $statusCode = 200,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
