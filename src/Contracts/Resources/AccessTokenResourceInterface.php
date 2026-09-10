<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\AccessToken\AccessTokenDto;

interface AccessTokenResourceInterface
{
    public function exchangeWithOidc(string $code, string $nonce, bool $withHttpOnlyToken = false): AccessTokenDto;

    public function revokePendingToken(string $pendingToken): AccessTokenDto;
}
