<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        string $email,
        string $password,
        string $name,
        string $username,
    ) {
        $this->options['json'] = [
            'email' => $email,
            'password' => $password,
            'name' => $name,
            'username' => $username,
        ];
    }

    public function url(): string
    {
        return 'api/users';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new UserDtoFactory();
    }
}
