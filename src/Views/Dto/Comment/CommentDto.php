<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Comment;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\CommentTypeEnum;
use Planka\Bridge\Traits\OutputDtoTrait;

class CommentDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly string $cardId,
        public readonly string $userId,
        public readonly ?CommentTypeEnum $type = null,
        public string $dataText = '',
        public string $text = '',
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
