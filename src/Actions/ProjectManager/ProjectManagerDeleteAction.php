<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\ProjectManager;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\ProjectManagerHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class ProjectManagerDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, ProjectManagerHydrateTrait;

    public function __construct(private readonly string $projectManagerId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/project-managers/{$this->projectManagerId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}