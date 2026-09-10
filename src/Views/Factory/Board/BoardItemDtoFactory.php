<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Enum\BoardDefaultViewEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;

final class BoardItemDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @see Payload structure:
     * array{
     *     id: string,
     *     projectId: string,
     *     position: int|float,
     *     name: string,
     *     defaultView?: ?string,
     *     defaultCardType?: ?string,
     *     limitCardTypesToDefaultOne?: ?bool,
     *     alwaysDisplayCardCreator?: ?bool,
     *     expandTaskListsByDefault?: ?bool,
     *     displayCardAges?: ?bool,
     *     createdAt?: ?string,
     *     updatedAt?: ?string
     * }
     */
    public function create(array $data): BoardItemDto
    {
        return new BoardItemDto(
            id: isset($data['id']) && is_string($data['id']) ? $data['id'] : null,
            projectId: isset($data['projectId']) && is_string($data['projectId']) ? $data['projectId'] : null,
            position: isset($data['position']) ? (int) $data['position'] : null,
            name: isset($data['name']) && is_string($data['name']) ? $data['name'] : null,
            defaultView: isset($data['defaultView']) && is_string($data['defaultView']) ? BoardDefaultViewEnum::tryFrom($data['defaultView']) : null,
            defaultCardType: isset($data['defaultCardType']) && is_string($data['defaultCardType']) ? BoardDefaultCardTypeEnum::tryFrom($data['defaultCardType']) : null,
            limitCardTypesToDefaultOne: (bool) ($data['limitCardTypesToDefaultOne'] ?? false),
            alwaysDisplayCardCreator: (bool) ($data['alwaysDisplayCardCreator'] ?? false),
            expandTaskListsByDefault: (bool) ($data['expandTaskListsByDefault'] ?? false),
            displayCardAges: (bool) ($data['displayCardAges'] ?? false),
            createdAt: isset($data['createdAt']) && is_string($data['createdAt']) ? $this->convertToDateTime($data['createdAt']) : null,
            updatedAt: isset($data['updatedAt']) && is_string($data['updatedAt']) ? $this->convertToDateTime($data['updatedAt']) : null,
            _rawResponse: $data,
        );
    }
}
