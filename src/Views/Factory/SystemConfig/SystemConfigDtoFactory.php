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
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
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
     * }
     */
    public function create(array $data): SystemConfigDto
    {
        $data = $data['item'] ?? $data;

        return new SystemConfigDto(
            id: (string) ($data['id'] ?? ''),
            smtpHost: isset($data['smtpHost']) && is_string($data['smtpHost']) ? $data['smtpHost'] : null,
            smtpPort: isset($data['smtpPort']) ? (int) $data['smtpPort'] : null,
            smtpName: isset($data['smtpName']) && is_string($data['smtpName']) ? $data['smtpName'] : null,
            smtpSecure: (bool) ($data['smtpSecure'] ?? false),
            smtpTlsRejectUnauthorized: (bool) ($data['smtpTlsRejectUnauthorized'] ?? true),
            smtpUser: isset($data['smtpUser']) && is_string($data['smtpUser']) ? $data['smtpUser'] : null,
            smtpPassword: isset($data['smtpPassword']) && is_string($data['smtpPassword']) ? $data['smtpPassword'] : null,
            smtpFrom: isset($data['smtpFrom']) && is_string($data['smtpFrom']) ? $data['smtpFrom'] : null,
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
