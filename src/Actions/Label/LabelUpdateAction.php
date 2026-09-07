<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Label;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Views\Factory\Label\LabelDtoFactory;

final class LabelUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    public function __construct(
        private readonly string $labelId,
        private readonly string $name,
        private readonly LabelColorEnum $color,
    ) {}

    public function url(): string
    {
        return "api/labels/{$this->labelId}";
    }

    public function getOptions(): array
    {
        return [
            'json' => [
                'name' => $this->name,
                'color' => $this->color->value,
            ],
        ];
    }

    public function getFactory(): OutputInterface
    {
        return new LabelDtoFactory();
    }
}
