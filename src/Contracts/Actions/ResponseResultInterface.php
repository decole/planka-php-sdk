<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Actions;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ResponseResultInterface
{
    public function hydrate(ResponseInterface $response): mixed;
}
