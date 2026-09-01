<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\NotificationService;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class NotificationServiceTestAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(private readonly string $id) {}

    public function url(): string
    {
        return "api/notification-services/{$this->id}/test";
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
