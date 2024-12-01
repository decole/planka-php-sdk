<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Actions;

interface AuthenticateInterface
{
    public function getToken(): string;
}
