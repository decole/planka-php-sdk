<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardActionIncludedDto;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

use function Fp\Collection\map;

final class CardActionIncludedDtoFactory implements OutputInterface
{
    /**\
     * @see Payload structure:
     * array{
     *     users?: array
     * }
     */
    public function create(array $data): CardActionIncludedDto
    {
        /** @var list<array> $users */
        $users = $data['users'] ?? [];

        return new CardActionIncludedDto(
            users: map($users, fn(array $item) => (new UserDtoFactory())->create($item)),
            _rawResponse: $data,
        );
    }
}
