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

    public function create(array $data): BoardItemDto
    {
        return new BoardItemDto(
            id: $data['id'] ?? null,
            projectId: $data['projectId'] ?? null,
            position: isset($data['position']) ? (int) $data['position'] : null,
            name: $data['name'] ?? null,
            defaultView: isset($data['defaultView']) ? BoardDefaultViewEnum::tryFrom($data['defaultView']) : null,
            defaultCardType: isset($data['defaultCardType']) ? BoardDefaultCardTypeEnum::tryFrom($data['defaultCardType']) : null,
            limitCardTypesToDefaultOne: (bool) ($data['limitCardTypesToDefaultOne'] ?? false),
            alwaysDisplayCardCreator: (bool) ($data['alwaysDisplayCardCreator'] ?? false),
            expandTaskListsByDefault: (bool) ($data['expandTaskListsByDefault'] ?? false),
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            _rawResponse: $data,
        );
    }
}
