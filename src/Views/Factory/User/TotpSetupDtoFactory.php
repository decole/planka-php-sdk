<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\User;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\User\TotpSetupDto;

final class TotpSetupDtoFactory implements OutputInterface
{
    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     secret?: ?string,
     *     provisioningUri?: ?string
     * }
     */
    public function create(array $data): TotpSetupDto
    {
        $item = $data['item'] ?? $data;

        return new TotpSetupDto(
            secret: isset($item['secret']) && is_string($item['secret']) ? $item['secret'] : null,
            provisioningUri: isset($item['provisioningUri']) && is_string($item['provisioningUri']) ? $item['provisioningUri'] : null,
            _rawResponse: $data,
        );
    }
}
