<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class GetBootstrapAction implements ActionInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/bootstrap';
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
