<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;

final class ProjectUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $projectId,
        private readonly string $name,
    ) {}

    public function url(): string
    {
        return "api/projects/{$this->projectId}";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->name,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new ProjectDtoFactory();
    }
}
