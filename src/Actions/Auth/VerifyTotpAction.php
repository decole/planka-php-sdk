<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class VerifyTotpAction implements ActionInterface
{
    public function __construct(
        private readonly string $pendingToken,
        private readonly string $code,
        private readonly bool $trustDevice = false,
    ) {}

    public function url(): string
    {
        return 'api/access-tokens/verify-totp';
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'pendingToken' => $this->pendingToken,
                'code' => $this->code,
                'trustDevice' => $this->trustDevice,
            ],
        ];
    }
}
