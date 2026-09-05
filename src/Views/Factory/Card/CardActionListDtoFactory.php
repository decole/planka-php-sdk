<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardActionItemDto;
use Planka\Bridge\Views\Dto\Card\CardActionListDto;

use function Fp\Collection\map;

final class CardActionListDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     items: array,
     *     included?: array
     * } $data
     */
    public function create(array $data): CardActionListDto
    {
        return new CardActionListDto(
            items: $this->getItems($data),
            included: (new CardActionIncludedDtoFactory())->create($data['included'] ?? null),
            _rawResponse: $data,
        );
    }

    /**
     * @return list<CardActionItemDto>
     */
    private function getItems(array $data): array
    {
        return map($data['items'] ?? [], fn(array $item) => (new CardActionItemDtoFactory())->create($item));
    }
}
