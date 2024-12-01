<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\ProjectManager;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\ProjectManagerHydrateTrait;
use Planka\Bridge\Traits\AuthenticateTrait;

final class ProjectManagerCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use ProjectManagerHydrateTrait;

    public function __construct(
        private readonly string $projectId,
        private readonly string $userId,
        string $token,
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/projects/{$this->projectId}/managers";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'userId' => $this->userId,
            ],
        ];
    }
}
