<?php

declare(strict_types=1);

namespace Planka\Bridge\Traits;

trait AuthenticateTrait
{
    private string $token;

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }
}
