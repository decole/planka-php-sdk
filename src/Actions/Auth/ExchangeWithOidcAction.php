<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Traits\AccessTokenHydrateTrait;

final class ExchangeWithOidcAction implements ActionInterface, ResponseResultInterface
{
    use AccessTokenHydrateTrait;

    public function __construct(
        private readonly string $code,
        private readonly string $nonce,
        private readonly bool $withHttpOnlyToken = false,
    ) {}

    public function url(): string
    {
        return 'api/access-tokens/exchange-with-oidc';
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'code' => $this->code,
                'nonce' => $this->nonce,
                'withHttpOnlyToken' => $this->withHttpOnlyToken,
            ],
        ];
    }
}
