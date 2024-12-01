<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\ProjectHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class ProjectDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use ProjectHydrateTrait;

    public function __construct(private readonly string $projectId, string $token)
    {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/projects/{$this->projectId}";
    }

    public function getOptions(): array
    {
        return [];
    }
}
