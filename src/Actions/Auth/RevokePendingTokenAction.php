<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AccessTokenHydrateTrait;

final class RevokePendingTokenAction implements ActionInterface, ResponseResultInterface
{
    use AccessTokenHydrateTrait;

    public function __construct(private readonly string $pendingToken) {}

    public function url(): string
    {
        return 'api/access-tokens/revoke-pending-token';
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'pendingToken' => $this->pendingToken,
            ],
        ];
    }
}
