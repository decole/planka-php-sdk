<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Label;

use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Traits\AuthenticateTrait;
use Planka\Bridge\Traits\LabelHydrateTrait;
use Planka\Bridge\Enum\LabelColorEnum;

final class LabelCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    use AuthenticateTrait;
    use LabelHydrateTrait;

    public function __construct(
        private readonly string $boardId,
        private readonly string $name,
        private readonly LabelColorEnum $color,
        private readonly int $position,
        string $token,
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
