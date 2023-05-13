<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\Card\CardTasksDto;
use Planka\Bridge\Views\Dto\Card\CardLabelDto;
use Planka\Bridge\Views\Dto\Label\LabelDto;
use Planka\Bridge\Views\Dto\List\ListDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use Planka\Bridge\Views\Dto\Card\CardDto;

final class BoardIncludedDto
{
    /**
     * @param list<UserDto> $users
     * @param list<BoardMembershipDto> $boardMemberships
     * @param list<LabelDto> $labels
     * @param list<ListDto> $lists
     * @param list<CardDto> $cards
     * @param list<CardMembershipDto> $cardMemberships
     * @param list<CardLabelDto> $cardLabels
     * @param list<CardTasksDto> $tasks
     * @param list<AttachmentDto> $attachments
     * @param list<ProjectDto> $projects
     */
    public function __construct(
        public array $users,
        public array $boardMemberships,
        public array $labels,
        public array $lists,
        public array $cards,
        public array $cardMemberships,
        public array $cardLabels,
        public array $tasks,
        public array $attachments,
        public array $projects,
    ) {
    }
}