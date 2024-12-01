<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;

class CardIncludedDto
{
    /**
     * @param list<CardMembershipDto> $cardMemberships
     * @param list<CardLabelDto> $cardLabels
     * @param list<CardTaskDto> $tasks
     * @param list<AttachmentDto> $attachments
     */
    public function __construct(
        public array $cardMemberships,
        public array $cardLabels,
        public array $tasks,
        public array $attachments
    ) {
    }
}
