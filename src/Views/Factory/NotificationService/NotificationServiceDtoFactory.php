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
     * @param array{
     *     id: string,
     *     userId?: ?string,
     *     boardId?: ?string,
     *     url: string,
     *     format: string,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * } $data
     */
    public function create(array $data): NotificationServiceDto
    {
        return new NotificationServiceDto(
            id: $data['id'],
            userId: $data['userId'] ?? null,
            boardId: $data['boardId'] ?? null,
            url: $data['url'],
            format: NotificationServiceFormatEnum::from($data['format']),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
        );
    }
}
