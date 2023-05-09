<?php

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Views\Factory\User\UserListDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UserListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return 'api/users';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function hydrate(ResponseInterface $response): array
    {
        return (new UserListDtoFactory())->create($response->toArray());
    }
}