<?php

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UserViewAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(string $token, private readonly string $id)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/users/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
    }

    public function hydrate(ResponseInterface $response): mixed
    {
        return (new UserDtoFactory())->create($response->toArray()['item']);
    }
}