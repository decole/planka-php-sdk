<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\CustomField;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

class CustomFieldDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly string $id,
        public readonly ?string $baseCustomFieldGroupId,
        public readonly ?string $customFieldGroupId,
        public int $position,
        public string $name,
        public bool $showOnFrontOfCard,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
