<?php

declare(strict_types=1);

namespace Planka\Bridge\Builders;

use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Inputs\CardCreateInput;

final class CardBuilder
{
    private string $name = '';

    private ?int $position = 65536;

    private ?string $description = null;

    private ?\DateTimeImmutable $dueDate = null;

    private ?bool $isDueCompleted = null;

    private ?BoardDefaultCardTypeEnum $type = null;

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

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setDueDate(?\DateTimeImmutable $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function setIsDueCompleted(?bool $isDueCompleted): self
    {
        $this->isDueCompleted = $isDueCompleted;

        return $this;
    }

    public function setType(?BoardDefaultCardTypeEnum $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function build(): CardCreateInput
    {
        return new CardCreateInput(
            name: $this->name,
            position: $this->position,
            description: $this->description,
            dueDate: $this->dueDate,
            isDueCompleted: $this->isDueCompleted,
            type: $this->type,
        );
    }
}
