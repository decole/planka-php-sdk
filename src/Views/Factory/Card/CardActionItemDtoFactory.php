<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardActionItemDto;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Enum\CommentTypeEnum;

final class CardActionItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     type: string,
     *     data: array{text: string},
     *     cardId: string,
     *     userId: string
     * } $data
     */
    public function create(array $data): CardActionItemDto
    {
        $typeStr = $data['type'] ?? 'commentCard';
        $type = CommentTypeEnum::tryFrom($typeStr) ?? CommentTypeEnum::COMMENT_CARD;

        return new CardActionItemDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            type: $type,
            dataText: $data['data']['text'] ?? $data['text'] ?? '',
            cardId: $data['cardId'] ?? '',
            userId: $data['userId'] ?? '',
        );
    }
}
