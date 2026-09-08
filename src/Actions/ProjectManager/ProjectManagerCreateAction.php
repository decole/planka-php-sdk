<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\ProjectManager;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Project\ProjectManagerDtoFactory;

final class ProjectManagerCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $projectId,
        private readonly string $userId,
    ) {}

    public function url(): string
    {
        return "api/projects/{$this->projectId}/project-managers";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'userId' => $this->userId,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new ProjectManagerDtoFactory();
    }
}
