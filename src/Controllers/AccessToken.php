<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Auth\ExchangeWithOidcAction;
use Planka\Bridge\Actions\Auth\RevokePendingTokenAction;
use Planka\Bridge\Contracts\Resources\AccessTokenResourceInterface;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;

final class AccessToken implements AccessTokenResourceInterface
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /** 'POST /api/access-tokens/exchange-with-oidc' */
    public function exchangeWithOidc(string $code, string $nonce, bool $withHttpOnlyToken = false): AccessTokenDto
    {
        return $this->client->post(new ExchangeWithOidcAction($code, $nonce, $withHttpOnlyToken));
    }

    /** 'POST /api/access-tokens/revoke-pending-token' */
    public function revokePendingToken(string $pendingToken): AccessTokenDto
    {
        return $this->client->post(new RevokePendingTokenAction($pendingToken));
    }
}
