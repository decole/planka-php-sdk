<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Comment;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\CommentTypeEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Comment\CommentDto;

final class CommentDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     cardId: string,
     *     userId?: ?string,
     *     text?: ?string,
     *     type?: ?string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): CommentDto
    {
        $data = $data['item'] ?? $data;
        $text = $data['text'] ?? $data['data']['text'] ?? '';
        $type = null;

        if (isset($data['type']) && is_string($data['type'])) {
            $type = CommentTypeEnum::tryFrom($data['type']);
        }

        return new CommentDto(
            id: (string) $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            cardId: (string) ($data['cardId'] ?? ''),
            userId: (string) ($data['userId'] ?? ''),
            type: $type,
            dataText: (string) $text,
            text: (string) $text,
            _rawResponse: $data,
        );
    }
}
