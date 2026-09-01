<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UserCreateApiKeyAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(private readonly string $userId) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/api-key";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function hydrate(ResponseInterface $response): array
    {
        return $response->toArray();
    }
}
