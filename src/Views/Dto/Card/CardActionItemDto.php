<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Enum\CommentTypeEnum;

class CardActionItemDto implements OutputDtoInterface
{
    public function __construct(
        public readonly string $id,
        public readonly \DateTimeImmutable $createdAt,
        public readonly ?\DateTimeImmutable $updatedAt,
        public readonly CommentTypeEnum $type,
        public readonly string $dataText,
        public readonly ?string $cardId = null,
        public readonly ?string $userId = null,
        public readonly ?string $boardId = null,
        public readonly array $data = [],
        public readonly array $_rawResponse = [],
    ) {}
}
