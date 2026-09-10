<?php

declare(strict_types=1);

namespace Planka\Bridge\Builders;

use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;
use Planka\Bridge\Inputs\BoardCreateInput;

final class BoardBuilder
{
    private string $name = '';

    private int $position = 65536;

    private ?BoardDefaultViewEnum $defaultView = null;

    private ?BoardDefaultCardTypeEnum $defaultCardType = null;

    private ?bool $limitCardTypesToDefaultOne = null;

    private ?bool $alwaysDisplayCardCreator = null;

    private ?bool $expandTaskListsByDefault = null;

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

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setDefaultView(?BoardDefaultViewEnum $defaultView): self
    {
        $this->defaultView = $defaultView;

        return $this;
    }

    public function setDefaultCardType(?BoardDefaultCardTypeEnum $defaultCardType): self
    {
        $this->defaultCardType = $defaultCardType;

        return $this;
    }

    public function setLimitCardTypesToDefaultOne(?bool $limitCardTypesToDefaultOne): self
    {
        $this->limitCardTypesToDefaultOne = $limitCardTypesToDefaultOne;

        return $this;
    }

    public function setAlwaysDisplayCardCreator(?bool $alwaysDisplayCardCreator): self
    {
        $this->alwaysDisplayCardCreator = $alwaysDisplayCardCreator;

        return $this;
    }

    public function setExpandTaskListsByDefault(?bool $expandTaskListsByDefault): self
    {
        $this->expandTaskListsByDefault = $expandTaskListsByDefault;

        return $this;
    }

    public function build(): BoardCreateInput
    {
        return new BoardCreateInput(
            name: $this->name,
            position: $this->position,
            defaultView: $this->defaultView,
            defaultCardType: $this->defaultCardType,
            limitCardTypesToDefaultOne: $this->limitCardTypesToDefaultOne,
            alwaysDisplayCardCreator: $this->alwaysDisplayCardCreator,
            expandTaskListsByDefault: $this->expandTaskListsByDefault,
        );
    }
}
