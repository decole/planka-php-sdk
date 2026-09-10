<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Config;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

class ConfigDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly string $id,
        public ?string $smtpHost,
        public ?int $smtpPort,
        public ?string $smtpName,
        public bool $smtpSecure,
        public bool $smtpTlsRejectUnauthorized,
        public ?string $smtpUser,
        public ?string $smtpPassword,
        public ?string $smtpFrom,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
