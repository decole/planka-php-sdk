<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Board;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Views\Factory\Board\BoardDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class BoardViewAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(string $token, private readonly int $id)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/boards/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function hydrate(ResponseInterface $response): mixed
    {
        return (new BoardDtoFactory())->create($response->toArray());
    }
}