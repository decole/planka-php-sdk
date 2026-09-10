<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\User;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\User\TrustedDeviceDto;

final class TrustedDeviceDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string,
     *     userId?: ?string,
     *     name?: ?string,
     *     ipAddress?: ?string
     * }
     */
    public function create(array $data): TrustedDeviceDto
    {
        $item = $data['item'] ?? $data;

        return new TrustedDeviceDto(
            id: (string) $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            userId: isset($item['userId']) && is_string($item['userId']) ? $item['userId'] : null,
            name: isset($item['name']) && is_string($item['name']) ? $item['name'] : null,
            ipAddress: isset($item['ipAddress']) && is_string($item['ipAddress']) ? $item['ipAddress'] : null,
            _rawResponse: $data,
        );
    }
}
