<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Project;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\ProjectHydrateTrait;

final class ProjectDeleteAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait, ProjectHydrateTrait;

    public function __construct(
        string $token,
        private readonly string $boardId,
        private readonly string $name,
        private readonly LabelColorEnum $color,
        private readonly int $position
    ) {
        $this->setToken($token);
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}/labels";
    }

    public function getOptions(): array
    {
        return [
            'body' => [
                'color' => $this->color->value,
                'name' => $this->name,
                'position' => $this->position,
            ],
        ];
    }
}