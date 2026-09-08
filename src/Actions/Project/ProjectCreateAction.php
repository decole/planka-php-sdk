<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;

final class ProjectCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $name,
        private readonly ProjectTypeEnum $type = ProjectTypeEnum::PRIVATE,
        private readonly ?string $description = null,
    ) {}

    public function url(): string
    {
        return 'api/projects';
    }

    public function getOptions(): array
    {
        $body = [
            'name' => $this->name,
            'type' => $this->type->value,
        ];

        if (null !== $this->description) {
            $body['description'] = $this->description;
        }

        return [
            'json' => $body,
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new ProjectDtoFactory();
    }
}
