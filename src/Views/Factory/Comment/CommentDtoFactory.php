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
     * @return CommentDto
     */
    public function create(array $data): CommentDto
    {
        return new CommentDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            cardId: $data['cardId'],
            userId: $data['userId'],
            type: CommentTypeEnum::from($data['type']),
            dataText: $data['data']['text']
        );
    }
}