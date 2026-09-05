<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Comment;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Comment\CommentDto;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Enum\CommentTypeEnum;

final class CommentDtoFactory implements OutputInterface
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
    public function create(array $data): CommentDto
    {
        $text = $data['text'] ?? $data['data']['text'] ?? '';
        $type = isset($data['type']) && is_string($data['type']) ? CommentTypeEnum::tryFrom($data['type']) : null;

        return new CommentDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            cardId: $data['cardId'] ?? '',
            userId: $data['userId'] ?? '',
            type: $type,
            dataText: $text,
            _rawResponse: $data,
        );
    }
}
