<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\SystemConfig;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\SystemConfig\SystemConfigDto;

final class SystemConfigDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     smtpHost?: ?string,
     *     smtpPort?: ?int,
     *     smtpName?: ?string,
     *     smtpSecure?: bool,
     *     smtpTlsRejectUnauthorized?: bool,
     *     smtpUser?: ?string,
     *     smtpPassword?: ?string,
     *     smtpFrom?: ?string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * } $data
     */
    public function create(array $data): SystemConfigDto
    {
        return new SystemConfigDto(
            id: $data['id'],
            smtpHost: $data['smtpHost'] ?? null,
            smtpPort: isset($data['smtpPort']) ? (int) $data['smtpPort'] : null,
            smtpName: $data['smtpName'] ?? null,
            smtpSecure: (bool) ($data['smtpSecure'] ?? false),
            smtpTlsRejectUnauthorized: (bool) ($data['smtpTlsRejectUnauthorized'] ?? true),
            smtpUser: $data['smtpUser'] ?? null,
            smtpPassword: $data['smtpPassword'] ?? null,
            smtpFrom: $data['smtpFrom'] ?? null,
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
        );
    }
}
