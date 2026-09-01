<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\CustomField;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class BaseCustomFieldGroupDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly string $projectId,
        public string $name,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {}
}
