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
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     name: string,
     *     cardId: string,
     *     url: string,
     *     coverUrl: ?string,
     *     creatorUserId: string,
     *     image: array{height: int, width: int}
     * }|null $data
     * @return ?AttachmentDto
     */
    public function create(?array $data): ?AttachmentDto
    {
        if (empty($data)) {
            return null;
        }

        return new AttachmentDto(
            id: $data['id'],
            name: $data['name'],
            cardId: $data['cardId'],
            url: $data['url'],
            creatorUserId: $data['creatorUserId'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            coverUrl: $data['coverUrl'],
            image: (new ImageDtoFactory())->create($data['image'] ?? null)
        );
    }
}