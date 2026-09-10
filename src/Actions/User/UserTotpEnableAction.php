<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class UserTotpEnableAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $currentPassword,
        private readonly string $code,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/totp/enable";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'currentPassword' => $this->currentPassword,
                'code' => $this->code,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new UserDtoFactory();
    }
}
