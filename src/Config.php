<?php

declare(strict_types=1);

namespace Planka\Bridge;

use Planka\Bridge\Auth\InMemoryTokenStorage;
use Planka\Bridge\Auth\TokenStorageInterface;

final class Config
{
    final public const DATE_FORMAT = 'Y-m-d\TH:i:s.v\Z';

    private readonly TokenStorageInterface $tokenStorage;

    private readonly int $port;

    public function __construct(
        private readonly ?string $user = null,
        private readonly ?string $password = null,
        private readonly string $baseUri = '',
        ?int $port = null,
        ?string $apiKey = null,
        ?TokenStorageInterface $tokenStorage = null,
    ) {
        $this->tokenStorage = $tokenStorage ?? new InMemoryTokenStorage(apiKey: $apiKey);

        if (null !== $port) {
            $this->port = $port;
        } else {
            $parsedPort = parse_url($this->baseUri, PHP_URL_PORT);
            $parsedScheme = parse_url($this->baseUri, PHP_URL_SCHEME);
            $this->port = $parsedPort ?? ('https' === $parsedScheme ? 443 : 80);
        }
    }

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
        return $this->tokenStorage->getAuthToken();
    }

    public function setAuthToken(?string $authToken): void
    {
        $this->tokenStorage->setAuthToken($authToken);
    }

    public function getApiKey(): ?string
    {
        return $this->tokenStorage->getApiKey();
    }

    public function setApiKey(?string $apiKey): void
    {
        $this->tokenStorage->setApiKey($apiKey);
    }

    public function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
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
