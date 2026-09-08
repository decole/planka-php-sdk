<?php

declare(strict_types=1);

namespace Planka\Bridge\Auth;

interface TokenStorageInterface
{
    public function getAuthToken(): ?string;

    public function setAuthToken(?string $authToken): void;

    public function getApiKey(): ?string;

    public function setApiKey(?string $apiKey): void;
}
