<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardDto;

final class BoardDtoFactory implements OutputInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): BoardDto
    {
        /** @var array<string, mixed> $itemData */
        $itemData = isset($data['item']) && is_array($data['item']) ? $data['item'] : $data;

        /** @var array<string, mixed>|null $includedData */
        $includedData = isset($data['included']) && is_array($data['included']) ? $data['included'] : null;

        return new BoardDto(
            item: (new BoardItemDtoFactory())->create($itemData),
            included: null !== $includedData ? (new BoardIncludedDtoFactory())->create($includedData) : null,
            _rawResponse: $data,
        );
    }
}
