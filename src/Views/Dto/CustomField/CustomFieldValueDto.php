<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\CustomField;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class CustomFieldValueDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $cardId,
        public readonly string $customFieldGroupId,
        public readonly string $customFieldId,
        public string $content,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {}
}
