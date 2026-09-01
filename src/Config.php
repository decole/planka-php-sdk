<?php

declare(strict_types=1);

namespace Planka\Bridge;

final class Config
{
    private ?string $authToken = null;

    public function __construct(
        private readonly ?string $user = null,
        private readonly ?string $password = null,
        private readonly string $baseUri = '',
        private readonly int $port = 80,
        private ?string $apiKey = null,
    ) {}

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function getPassword(): ?string
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

    public function getApiKey(): ?string
    {
        return $this->apiKey;
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->apiKey = $apiKey;
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
