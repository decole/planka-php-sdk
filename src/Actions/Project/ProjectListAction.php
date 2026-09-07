<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Project\ProjectListDtoFactory;

final class ProjectListAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function url(): string
    {
        return 'api/projects';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new ProjectListDtoFactory();
    }
}
