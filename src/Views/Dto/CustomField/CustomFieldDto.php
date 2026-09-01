<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\CustomField;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class CustomFieldDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly ?string $baseCustomFieldGroupId,
        public readonly ?string $customFieldGroupId,
        public int $position,
        public string $name,
        public bool $showOnFrontOfCard,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {}
}
