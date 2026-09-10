<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\User;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class UserTotpRecoveryCodesAction implements ActionInterface
{
    public function __construct(
        private readonly string $userId,
        private readonly string $currentPassword,
        private readonly string $code,
    ) {}

    public function url(): string
    {
        return "api/users/{$this->userId}/totp/recovery-codes";
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
}
