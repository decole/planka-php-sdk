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
     * @see Payload structure:
     * array{
     *     items: array,
     *     included?: array
     * }
     */
    public function create(array $data): CardActionListDto
    {
        $included = [];

        if (isset($data['included']) && is_array($data['included'])) {
            $included = $data['included'];
        }

        return new CardActionListDto(
            items: $this->getItems($data),
            included: (new CardActionIncludedDtoFactory())->create($included),
            _rawResponse: $data,
        );
    }

    /**
     * @return list<CardActionItemDto>
     */
    private function getItems(array $data): array
    {
        return array_values(map($data['items'] ?? [], fn(array $item) => (new CardActionItemDtoFactory())->create($item)));
    }
}
