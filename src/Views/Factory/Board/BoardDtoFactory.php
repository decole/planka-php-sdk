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
        $itemData = $data;

        if (isset($data['item']) && is_array($data['item'])) {
            $itemData = $data['item'];
        }

        $included = null;

        if (isset($data['included']) && is_array($data['included'])) {
            $included = (new BoardIncludedDtoFactory())->create($data['included']);
        }

        return new BoardDto(
            item: (new BoardItemDtoFactory())->create($itemData),
            included: $included,
            _rawResponse: $data,
        );
    }
}
