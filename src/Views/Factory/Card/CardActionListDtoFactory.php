<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use Planka\Bridge\Views\Dto\Card\CardActionItemDto;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use function Fp\Collection\map;

final class CardActionListDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     items: array{
     *         id: string,
     *         createdAt: string,
     *         updatedAt: ?string,
     *         type: string,
     *         data: array{text: string},
     *         cardId: string,
     *         userId: string
     *     },
     *     included: array{
     *         users: array {
     *             id: string,
     *             createdAt: string,
     *             updatedAt: ?string,
     *             email: string,
     *             isAdmin: bool,
     *             name: string,
     *             username: string,
     *             phone: ?string,
     *             organization: ?string,
     *             language: string,
     *             subscribeToOwnCards: bool,
     *             deletedAt: ?string,
     *             avatarUrl: ?string,
     *         }
     *     }
     * } $data
     * @return CardActionListDto
     */
    public function create(array $data): CardActionListDto
    {
        return new CardActionListDto(
            items: $this->getItems($data),
            included: $this->getIncluded($data)
        );
    }

    /**
     * @return list<CardActionItemDto>
     */
    private function getItems(array $data): array
    {
        return map($data['items'] ?? [], fn(array $item) => (new CardActionItemDtoFactory())->create($item));
    }

    /**
     * @return list<UserDto>
     */
    private function getIncluded(array $data): array
    {
        return map($data['included']['users'] ?? [], fn(array $item) => (new UserDtoFactory())->create($item));
    }
}