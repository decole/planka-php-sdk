<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\User;

final class TrustedDeviceDto
{
    /**
     * @param array<string, mixed> $_rawResponse
     */
    public function __construct(
        public readonly string $id,
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
        public readonly ?string $userId = null,
        public readonly ?string $name = null,
        public readonly ?string $ipAddress = null,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
