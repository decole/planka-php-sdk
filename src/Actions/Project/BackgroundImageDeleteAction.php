<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class BackgroundImageDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(
        private readonly string $imageId,
        string $token = '',
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/background-images/{$this->imageId}";
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
