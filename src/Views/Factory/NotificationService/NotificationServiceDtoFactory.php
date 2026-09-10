<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\NotificationService;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\NotificationServiceFormatEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\NotificationService\NotificationServiceDto;

final class NotificationServiceDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     userId?: ?string,
     *     boardId?: ?string,
     *     url: string,
     *     format: string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): NotificationServiceDto
    {
        $data = $data['item'] ?? $data;

        return new NotificationServiceDto(
            id: (string) $data['id'],
            userId: isset($data['userId']) && is_string($data['userId']) ? $data['userId'] : null,
            boardId: isset($data['boardId']) && is_string($data['boardId']) ? $data['boardId'] : null,
            url: (string) $data['url'],
            format: NotificationServiceFormatEnum::from((string) $data['format']),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
