<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Background;

use Planka\Bridge\Views\Dto\Background\BackgroundImageDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;

final class BackgroundImageDtoFactory implements OutputInterface
{
    use \Planka\Bridge\Traits\DateConverterTrait;

    /**
     * @param array<string, mixed>|null $data
     *
     * @see Payload structure:
     *      array{
     *          id?: ?string,
     *          projectId?: ?string,
     *          size?: ?string,
     *          url?: ?string,
     *          coverUrl?: ?string,
     *          thumbnailUrls?: array,
     *          createdAt?: ?string,
     *          updatedAt?: ?string
     *      }
     */
    public function create(?array $data): ?BackgroundImageDto
    {
        if (empty($data)) {
            return null;
        }

        $data = $data['item'] ?? $data;

        return new BackgroundImageDto(
            id: $data['id'] ?? null,
            projectId: $data['projectId'] ?? null,
            size: isset($data['size']) ? (string) $data['size'] : null,
            url: $data['url'] ?? null,
            coverUrl: $data['coverUrl'] ?? null,
            thumbnailUrls: $data['thumbnailUrls'] ?? [],
            createdAt: isset($data['createdAt']) ? $this->convertToDateTime($data['createdAt']) : null,
            updatedAt: isset($data['updatedAt']) ? $this->convertToDateTime($data['updatedAt']) : null,
            _rawResponse: $data,
        );
    }
}
