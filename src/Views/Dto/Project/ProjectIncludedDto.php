<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Project;

use Planka\Bridge\Views\Factory\Project\ProjectManagerDto;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;
use Planka\Bridge\Views\Dto\User\UserDto;

class ProjectIncludedDto
{
    /**
     * @param list<UserDto>            $users
     * @param list<ProjectManagerDto>  $projectManagers
     * @param list<BoardItemDto>       $boards
     * @param list<BoardMembershipDto> $boardMemberships
     */
    public function __construct(
        public array $users,
        public array $projectManagers,
        public array $boards,
        public array $boardMemberships,
    ) {}
}
