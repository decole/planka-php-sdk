<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

trait AuthenticateTrait
{
    private string $token;

    final public function getToken(): string
    {
        return $this->token;
    }

    final public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
