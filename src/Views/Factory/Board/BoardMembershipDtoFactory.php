<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\Traits\DateConverterTrait;

final class BoardMembershipDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     role: string,
     *     canComment: ?bool,
     *     boardId: string,
     *     userId: string,
     * } $data
     */
    public function create(array $data): BoardMembershipDto
    {
        return new BoardMembershipDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            userId: $data['userId'],
            canComment: (bool) $data['canComment'],
            role: BoardMembershipRoleEnum::from($data['role']),
            boardId: $data['boardId'],
        );
    }
}
