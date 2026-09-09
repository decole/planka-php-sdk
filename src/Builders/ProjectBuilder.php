<?php

declare(strict_types=1);

namespace Planka\Bridge\Builders;

use Planka\Bridge\Enum\BackgroundGradientEnum;
use Planka\Bridge\Enum\BackgroundTypeEnum;
use Planka\Bridge\Enum\ProjectTypeEnum;
use Planka\Bridge\Inputs\ProjectCreateInput;

final class ProjectBuilder
{
    private string $name = '';

    private ?ProjectTypeEnum $type = null;

    private ?string $description = null;

    private ?BackgroundTypeEnum $backgroundType = null;

    private ?BackgroundGradientEnum $backgroundGradient = null;

    public function __construct(?string $name = null)
    {
        if (null !== $name) {
            $this->name = $name;
        }
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setType(?ProjectTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setBackgroundType(?BackgroundTypeEnum $backgroundType): self
    {
        $this->backgroundType = $backgroundType;

        return $this;
    }

    public function setBackgroundGradient(?BackgroundGradientEnum $backgroundGradient): self
    {
        $this->backgroundGradient = $backgroundGradient;

        return $this;
    }

    public function build(): ProjectCreateInput
    {
        return new ProjectCreateInput(
            name: $this->name,
            type: $this->type,
            description: $this->description,
            backgroundType: $this->backgroundType,
            backgroundGradient: $this->backgroundGradient,
        );
    }
}
