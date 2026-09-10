<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\User;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\User\ApiKeyDto;

final class ApiKeyDtoFactory implements OutputInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): ApiKeyDto
    {
        $key = $data['item'] ?? $data['apiKey'] ?? null;

        return new ApiKeyDto(
            apiKey: \is_string($key) ? $key : null,
            _rawResponse: $data,
        );
    }
}
