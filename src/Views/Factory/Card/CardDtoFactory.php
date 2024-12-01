<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Card\CardIncludedDto;
use Planka\Bridge\Views\Factory\Attachment\AttachmentDtoFactory;

use function Fp\Collection\map;

final class CardDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     item: array {
     *         id: string,
     *         createdAt: string,
     *         updatedAt: ?string,
     *         position: int,
     *         name: string,
     *         description: ?string,
     *         dueDate: ?string,
     *         isDueDateCompleted: ?bool,
     *         stopwatch: ?array,
     *         boardId: string,
     *         listId: ?string,
     *         creatorUserId: string,
     *         coverAttachmentId: ?string,
     *         isSubscribed: ?bool
     *     },
     *     included: array {
     *         cardMemberships: ?array{
     *             id: string,
     *             createdAt: string,
     *             updatedAt: ?string,
     *             cardId: string,
     *             userId: string
     *         },
     *         cardLabels: ?array {
     *             id: string,
     *             createdAt: string,
     *             updatedAt: ?string,
     *             cardId: string,
     *             labelId: string
     *         },
     *         tasks: ?array {
     *             id: string,
     *             createdAt: string,
     *             updatedAt: ?string,
     *             position: int,
     *             name: string,
     *             isCompleted: bool,
     *             cardId: string,
     *         },
     *         attachments: ?array {
     *             id: string,
     *             createdAt: string,
     *             updatedAt: ?string,
     *             updatedAt: array {
     *                 width: int,
     *                 height: int
     *             },
     *             name: string,
     *             cardId: string,
     *             creatorUserId: string,
     *             url: string,
     *             coverUrl: string
     *         },
     *     }
     * } $data
     */
    public function create(array $data): CardDto
    {
        $item = $data['item'];

        return new CardDto(
            id: $item['id'],
            createdAt: $this->convertToDateTime($item['createdAt']),
            updatedAt: $this->convertToDateTime($item['updatedAt']),
            position: (int) $item['position'],
            name: $item['name'],
            description: $item['description'],
            dueDate: $this->convertToDateTime($item['dueDate']),
            isDueDateCompleted: $item['isDueDateCompleted'],
            stopwatch: (new StopWatchDtoFactory())->create($item['stopwatch']),
            boardId: $item['boardId'],
            listId: $item['listId'],
            creatorUserId: $item['creatorUserId'],
            coverAttachmentId: $item['coverAttachmentId'],
            isSubscribed: (bool) ($item['isSubscribed'] ?? false),
            included: $this->getIncluded($data),
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
        );
    }
}
