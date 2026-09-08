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
     * @param array<string, mixed>|null $data
     *
     * @see Payload structure:
     *      array{
     *          id: string,
     *          createdAt: string,
     *          updatedAt: ?string,
     *          name: string,
     *          cardId: string,
     *          url?: ?string,
     *          coverUrl?: ?string,
     *          creatorUserId?: ?string,
     *          type?: ?string,
     *          data?: array,
     *          image?: array{height: int, width: int}
     *      }
     */
    public function create(?array $data): ?AttachmentDto
    {
        if (empty($data)) {
            return null;
        }

        $data = $data['item'] ?? $data;

        return new AttachmentDto(
            id: $data['id'],
            name: $data['name'],
            cardId: $data['cardId'],
            url: $data['url'] ?? null,
            creatorUserId: $data['creatorUserId'] ?? null,
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            coverUrl: $data['coverUrl'] ?? null,
            image: (new ImageDtoFactory())->create($data['image'] ?? null),
            type: $data['type'] ?? null,
            data: $data['data'] ?? [],
            _rawResponse: $data,
        );
    }
}
