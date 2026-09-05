<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\CommentTypeEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\CardActionItemDto;

final class CardActionItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string,
     *     type?: string,
     *     data?: array,
     *     cardId?: ?string,
     *     userId?: ?string,
     *     boardId?: ?string
     * } $data
     */
    public function create(array $data): CardActionItemDto
    {
        $typeStr = $data['type'] ?? 'commentCard';
        $type = CommentTypeEnum::tryFrom($typeStr) ?? CommentTypeEnum::COMMENT_CARD;

        $actionData = is_array($data['data'] ?? null) ? $data['data'] : [];
        $dataText = $data['data']['text'] ?? $data['text'] ?? '';

        return new CardActionItemDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            type: $type,
            dataText: $dataText,
            cardId: $data['cardId'] ?? null,
            userId: $data['userId'] ?? null,
            boardId: $data['boardId'] ?? null,
            data: $actionData,
            _rawResponse: $data,
        );
    }
}
