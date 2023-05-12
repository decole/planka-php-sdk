<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;

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
     *     userId: string
     * } $data
     * @return BoardMembershipDto
     */
    public function create(array $data): BoardMembershipDto
    {
        return new BoardMembershipDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            role: $data['role'],
            canComment: (bool)$data['canComment'],
            boardId: $data['boardId'],
            userId: $data['userId']
        );
    }
}