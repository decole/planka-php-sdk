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
     *      array{
     *          secret?: ?string,
     *          provisioningUri?: ?string
     *      }
     */
    public function create(array $data): TotpSetupDto
    {
        $item = $data['item'] ?? $data;

        return new TotpSetupDto(
            secret: $item['secret'] ?? null,
            provisioningUri: $item['provisioningUri'] ?? null,
            _rawResponse: $data,
        );
    }
}
