<?php

declare(strict_types=1);

namespace Planka\Bridge\Auth;

final class InMemoryTokenStorage implements TokenStorageInterface
{
    public function __construct(
        private ?string $authToken = null,
        private ?string $apiKey = null,
    ) {}

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
}
