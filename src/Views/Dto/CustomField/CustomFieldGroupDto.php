<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\CustomField;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class CustomFieldGroupDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $boardId,
        public readonly ?string $cardId,
        public readonly ?string $baseCustomFieldGroupId,
        public int $position,
        public ?string $name,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        public readonly array $_rawResponse = [],
    ) {}
}
