<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Auth;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class LogoutAction implements ActionInterface
{
    public function url(): string
    {
        return 'api/access-tokens/me';
    }

    public function getOptions(): array
    {
        return [];
    }
}
