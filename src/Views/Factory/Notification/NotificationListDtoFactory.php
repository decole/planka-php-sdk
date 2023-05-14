<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Notification;

use Planka\Bridge\Views\Dto\Notification\NotificationIncludedDto;
use Planka\Bridge\Views\Dto\Notification\NotificationItemDto;
use Planka\Bridge\Views\Dto\Notification\NotificationListDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use function Fp\Collection\map;

final class NotificationListDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     items: array,
     *     included: array
     * } $data
     * @return NotificationListDto
     */
    public function create(array $data): NotificationListDto
    {
        return new NotificationListDto(
            items: $this->getItems($data),
            included: $this->getIncluded($data)
        );
    }

    /**
     * @return list<NotificationItemDto>
     */
    private function getItems(array $data): array
    {
        return map($data['items'] ?? [], fn(array $item) => (new NotificationItemDtoFactory())->create($item));
    }

    private function getIncluded(array $data): NotificationIncludedDto
    {
        return (new NotificationIncludedDtoFactory())->create($data['included'] ?? []);
    }
}