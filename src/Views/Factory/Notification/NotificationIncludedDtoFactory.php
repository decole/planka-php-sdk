<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Notification;

use Planka\Bridge\Views\Dto\Notification\NotificationIncludedDto;
use Planka\Bridge\Views\Factory\Card\CardActionItemDtoFactory;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use Planka\Bridge\Views\Dto\Card\CardActionItemDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use function Fp\Collection\map;

final class NotificationIncludedDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     users: array,
     *     cards: array,
     *     actions: array
     * } $data
     * @return NotificationIncludedDto
     */
    public function create(array $data): NotificationIncludedDto
    {
        return new NotificationIncludedDto(
            users: $this->getUsers($data),
            cards: $this->getCards($data),
            actions: $this->getActions($data)
        );
    }

    /**
     * @return list<UserDto>
     */
    private function getUsers(array $data): array
    {
        return map($data['users'] ?? [], fn(array $item) => (new UserDtoFactory())->create($item));
    }

    /**
     * @return list<CardDto>
     */
    private function getCards(array $data): array
    {
        return map($data['cards'] ?? [],
            fn(array $item) => (new CardDtoFactory())->create(['item' => $item])
        );
    }

    /**
     * @return list<CardActionItemDto>
     */
    private function getActions(array $data): array
    {
        return map($data['actions'] ?? [],
            fn(array $item) => (new CardActionItemDtoFactory())->create($item)
        );
    }
}
