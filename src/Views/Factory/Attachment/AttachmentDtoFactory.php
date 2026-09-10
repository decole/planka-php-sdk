<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Attachment;

use Planka\Bridge\Views\Factory\Image\ImageDtoFactory;
use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;

final class AttachmentDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     name: string,
     *     cardId: string,
     *     url?: ?string,
     *     coverUrl?: ?string,
     *     creatorUserId?: ?string,
     *     type?: ?string,
     *     data?: array,
     *     image?: array{height: int, width: int}
     * }
     */
    public function create(array $data): AttachmentDto
    {
        /** @var array<string, mixed> $item */
        $item = isset($data['item']) && is_array($data['item']) ? $data['item'] : $data;

        return new AttachmentDto(
            id: (string) ($item['id'] ?? ''),
            name: (string) ($item['name'] ?? ''),
            cardId: (string) ($item['cardId'] ?? ''),
            url: isset($item['url']) && is_string($item['url']) ? $item['url'] : null,
            creatorUserId: isset($item['creatorUserId']) && is_string($item['creatorUserId']) ? $item['creatorUserId'] : null,
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            coverUrl: isset($item['coverUrl']) && is_string($item['coverUrl']) ? $item['coverUrl'] : null,
            image: isset($item['image']) && is_array($item['image']) ? (new ImageDtoFactory())->create($item['image']) : null,
            type: (string) ($item['type'] ?? 'file'),
            data: is_array($item['data'] ?? null) ? $item['data'] : [],
            _rawResponse: $data,
        );
    }
}
