<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;
use Planka\Bridge\Views\Dto\Project\ProjectIncludedDto;
use Planka\Bridge\Views\Dto\Project\ProjectManagerDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use Planka\Bridge\Views\Factory\Board\BoardItemDtoFactory;
use Planka\Bridge\Views\Factory\Board\BoardMembershipDtoFactory;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

use function Fp\Collection\map;

final class ProjectIncludedDtoFactory implements OutputInterface
{
    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): ProjectIncludedDto
    {
        return new ProjectIncludedDto(
            users: $this->getUsers($data),
            projectManagers: $this->getProjectManagers($data),
            boards: $this->getBoards($data),
            boardMemberships: $this->getBoardMemberships($data),
            _rawResponse: $data,
        );
    }

    /**
     * @return list<UserDto>
     */
    private function getUsers(array $data): array
    {
        /** @var list<array> $users */
        $users = $data['users'] ?? [];

        return map($users, fn(array $item) => (new UserDtoFactory())->create($item));
    }

    /**
     * @return list<ProjectManagerDto>
     */
    private function getProjectManagers(array $data): array
    {
        /** @var list<array> $projectManagers */
        $projectManagers = $data['projectManagers'] ?? [];

        return map($projectManagers, fn(array $item) => (new ProjectManagerDtoFactory())->create($item));
    }

    /**
     * @return list<BoardItemDto>
     */
    private function getBoards(array $data): array
    {
        /** @var list<array> $boards */
        $boards = $data['boards'] ?? [];

        return map($boards, fn(array $item) => (new BoardItemDtoFactory())->create($item));
    }

    /**
     * @return list<BoardMembershipDto>
     */
    private function getBoardMemberships(array $data): array
    {
        /** @var list<array> $boardMemberships */
        $boardMemberships = $data['boardMemberships'] ?? [];

        return map(
            $boardMemberships,
            fn(array $item) => (new BoardMembershipDtoFactory())->create($item),
        );
    }
}
