<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\AccessToken;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;

final class AccessTokenDtoFactory implements OutputInterface
{
    /**
     * @see Payload structure:
     * array{
     *     item?: string,
     *     token?: string,
     *     user?: array
     * }
     */
    public function create(array $data): AccessTokenDto
    {
        $token = null;

        if (array_key_exists('item', $data)) {
            if (is_string($data['item'])) {
                $token = $data['item'];
            }
        } elseif (array_key_exists('token', $data) && is_string($data['token'])) {
            $token = $data['token'];
        }

        return new AccessTokenDto(
            token: $token,
            _rawResponse: $data,
        );
    }
}
