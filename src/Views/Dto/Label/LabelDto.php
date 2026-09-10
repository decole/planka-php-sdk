<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Label;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Traits\OutputDtoTrait;

final class LabelDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly string $id,
        public readonly string $boardId,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public int $position,
        public string $name,
        public ?LabelColorEnum $color,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
