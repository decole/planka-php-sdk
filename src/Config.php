<?php

declare(strict_types=1);

namespace Planka\Bridge;

final class Config
{
    private ?string $authToken = null;

    public function __construct(
        private readonly string $user,
        private readonly string $password,
        private readonly string $baseUri,
        private readonly int $port,
    ) {}

    public function getUser(): string
    {
        return $this->user;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getAuthToken(): ?string
    {
        return $this->authToken;
    }

    public function setAuthToken(?string $authToken): void
    {
        $this->authToken = $authToken;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
    }

    public function getPort(): int
    {
        return $this->port;
    }
}
