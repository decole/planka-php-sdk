<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\SystemConfig;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class SystemConfigDto implements OutputDtoInterface
{
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
    ) {}
}
