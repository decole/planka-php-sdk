<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CardTask;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\CardTaskHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class CardTaskDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, CardTaskHydrateTrait;

    public function __construct(private readonly string $taskId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/tasks/{$this->taskId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}