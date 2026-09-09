<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

final class TotpSetupDto
{
    /**
     * @param array<string, mixed> $_rawResponse
     */
    public function __construct(
        public readonly ?string $secret = null,
        public readonly ?string $provisioningUri = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
