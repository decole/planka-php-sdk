<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;

final class BoardItemDto implements OutputDtoInterface
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $projectId,
        public readonly ?int $position,
        public readonly ?string $name,
        public readonly ?BoardDefaultViewEnum $defaultView = null,
        public readonly ?BoardDefaultCardTypeEnum $defaultCardType = null,
        public readonly bool $limitCardTypesToDefaultOne = false,
        public readonly bool $alwaysDisplayCardCreator = false,
        public readonly bool $expandTaskListsByDefault = false,
        public readonly ?\DateTimeImmutable $createdAt = null,
        public readonly ?\DateTimeImmutable $updatedAt = null,
        public readonly array $_rawResponse = [],
    ) {}
}
