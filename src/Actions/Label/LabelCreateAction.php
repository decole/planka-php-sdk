<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Label;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Views\Factory\Label\LabelDtoFactory;

final class LabelCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $boardId,
        private readonly string $name,
        private readonly LabelColorEnum $color,
        private readonly int $position,
    ) {}

    public function url(): string
    {
        return "api/boards/{$this->boardId}/labels";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->name,
                'color' => $this->color->value,
                'position' => $this->position,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new LabelDtoFactory();
    }
}
