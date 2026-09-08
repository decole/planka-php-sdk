<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\TotpSetupDtoFactory;

final class UserTotpSetupAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $currentPassword,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/totp/setup";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'currentPassword' => $this->currentPassword,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new TotpSetupDtoFactory();
    }
}
