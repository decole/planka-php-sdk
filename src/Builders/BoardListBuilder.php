<?php

declare(strict_types=1);

namespace Planka\Bridge\Builders;

use Planka\Bridge\Enum\ListColorEnum;
use Planka\Bridge\Enum\ListTypeEnum;
use Planka\Bridge\Inputs\BoardListPatchInput;

final class BoardListBuilder
{
    private ?string $name = null;

    private ?int $position = null;

    private string|ListTypeEnum|null $type = null;

    private string|ListColorEnum|null $color = null;

    public function __construct(?string $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setType(string|ListTypeEnum|null $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setColor(string|ListColorEnum|null $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function build(): BoardListPatchInput
    {
        return new BoardListPatchInput(
            name: $this->name,
            position: $this->position,
            type: $this->type,
            color: $this->color,
        );
    }
}
