<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Comment;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\CommentTypeEnum;

class CommentDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly string $cardId,
        public readonly string $userId,
        public readonly ?CommentTypeEnum $type = null,
        public string $dataText = '',
        public string $text = '',
        public readonly array $_rawResponse = [],
    ) {}
}
