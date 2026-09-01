<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class AuthenticateAction implements ActionInterface
{
    private array $options = [];

    public function __construct(string $username, string $password, bool $withHttpOnlyToken = false)
    {
        $this->options['json'] = [
            'emailOrUsername' => $username,
            'password' => $password,
            'withHttpOnlyToken' => $withHttpOnlyToken,
        ];
    }

    public function url(): string
    {
        return 'api/access-tokens';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
