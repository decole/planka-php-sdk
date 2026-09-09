<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Card;

use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;

class CardIncludedDto
{
    /**
     * @param list<CardMembershipDto|null> $cardMemberships
     * @param list<CardLabelDto|null>      $cardLabels
     * @param list<CardTaskDto|null>       $tasks
     * @param list<AttachmentDto|null>     $attachments
     */
    public function __construct(
        public array $cardMemberships,
        public array $cardLabels,
        public array $tasks,
        public array $attachments,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
