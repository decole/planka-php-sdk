<?php

namespace Planka\Bridge\Contracts\Actions;

use Symfony\Contracts\HttpClient\ResponseInterface;

interface ResponseResultInterface
{
    public function hydrate(ResponseInterface $response): mixed;
}