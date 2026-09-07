<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserUpdateEmailAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $email,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/email";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'email' => $this->email,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new UserDtoFactory();
    }
}
