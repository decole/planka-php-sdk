<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\AccessToken;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;

final class AccessTokenDtoFactory implements OutputInterface
{
    public function create(array $data): AccessTokenDto
    {
        $token = null;

        if (array_key_exists('item', $data)) {
            $token = is_string($data['item']) ? $data['item'] : null;
        } elseif (array_key_exists('token', $data) && is_string($data['token'])) {
            $token = $data['token'];
        }

        return new AccessTokenDto(
            token: $token,
            _rawResponse: $data,
        );
    }
}
