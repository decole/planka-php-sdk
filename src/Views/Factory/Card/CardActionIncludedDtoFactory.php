<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardActionIncludedDto;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

use function Fp\Collection\map;

final class CardActionIncludedDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     users?: array
     * }|null $data
     */
    public function create(?array $data): CardActionIncludedDto
    {
        if (null === $data) {
            return new CardActionIncludedDto();
        }

        return new CardActionIncludedDto(
            users: map($data['users'] ?? [], fn(array $item) => (new UserDtoFactory())->create($item)),
            _rawResponse: $data,
        );
    }
}
