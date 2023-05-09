<?php

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class UserCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;

    public function __construct(
        string $token,
        private readonly string $email,
        private readonly string $name,
        private readonly string $password,
        private readonly string $username
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return 'api/users';
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'email' => $this->email,
                'name' => $this->name,
                'password' => $this->password,
                'username' => $this->username,
            ],
        ];
    }

    public function hydrate(ResponseInterface $response): mixed
    {
        return (new UserDtoFactory())->create($response->toArray()['item']);
    }
}