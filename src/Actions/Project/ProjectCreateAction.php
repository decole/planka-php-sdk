<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Traits\ProjectHydrateTrait;

final class ProjectCreateAction implements ActionInterface, ResponseResultInterface
{
    use ProjectHydrateTrait;

    private array $options = [];

    public function __construct(
        string $name,
        ProjectTypeEnum $type = ProjectTypeEnum::PRIVATE,
        ?string $description = null,
    ) {
        $body = [
            'type' => $type->value,
            'name' => $name,
        ];

        if (null !== $description) {
            $body['description'] = $description;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return 'api/projects';
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
