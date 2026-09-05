<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Card\CardIncludedDto;
use Planka\Bridge\Views\Factory\Attachment\AttachmentDtoFactory;

use function Fp\Collection\map;

final class CardDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    public function create(array $data): CardDto
    {
        $item = $data['item'] ?? $data;

        $typeEnum = null;

        if (isset($item['type']) && is_string($item['type'])) {
            $typeEnum = BoardDefaultCardTypeEnum::tryFrom($item['type']);
        }

        $isDueCompleted = isset($item['isDueCompleted']) ? (bool) $item['isDueCompleted'] :
            (isset($item['isDueDateCompleted']) ? (bool) $item['isDueDateCompleted'] : null);

        return new CardDto(
            id: $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt'] ?? null),
            updatedAt: $this->convertToDateTime($item['updatedAt'] ?? null),
            position: (int) ($item['position'] ?? 0),
            name: $item['name'] ?? '',
            description: $item['description'] ?? null,
            dueDate: $this->convertToDateTime($item['dueDate'] ?? null),
            isDueDateCompleted: $isDueCompleted,
            stopwatch: (new StopWatchDtoFactory())->create($item['stopwatch'] ?? null),
            boardId: $item['boardId'] ?? '',
            listId: $item['listId'] ?? '',
            creatorUserId: $item['creatorUserId'] ?? '',
            coverAttachmentId: $item['coverAttachmentId'] ?? null,
            isSubscribed: (bool) ($item['isSubscribed'] ?? false),
            included: $this->getIncluded($data),
            type: $typeEnum,
            prevListId: $item['prevListId'] ?? null,
            commentsTotal: (int) ($item['commentsTotal'] ?? 0),
            isClosed: (bool) ($item['isClosed'] ?? false),
            listChangedAt: $this->convertToDateTime($item['listChangedAt'] ?? null),
            isDueCompleted: $isDueCompleted,
            _rawResponse: $data,
        );
    }

    private function getIncluded(array $data): CardIncludedDto
    {
        if (!isset($data['included'])) {
            return new CardIncludedDto(
                cardMemberships: [],
                cardLabels: [],
                tasks: [],
                attachments: [],
            );
        }

        return new CardIncludedDto(
            cardMemberships: map($data['included']['cardMemberships'] ?? [], fn(array $item) => (new CardMembershipDtoFactory())->create($item)),
            cardLabels: map($data['included']['cardLabels'] ?? [], fn(array $item) => (new CardLabelDtoFactory())->create($item)),
            tasks: map($data['included']['tasks'] ?? [], fn(array $item) => (new CardTaskDtoFactory())->create($item)),
            attachments: map($data['included']['attachments'] ?? [], fn(array $item) => (new AttachmentDtoFactory())->create($item)),
            _rawResponse: $data['included'],
        );
    }
}
