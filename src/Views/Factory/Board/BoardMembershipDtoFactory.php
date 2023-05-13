<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;

final class BoardMembershipDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     userId: string,
     *     canComment: ?bool,
     *     role: string,
     *     boardId: string
     * } $data
     * @return BoardMembershipDto
     */
    public function create(array $data): BoardMembershipDto
    {
        return new BoardMembershipDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            userId: $data['userId'],
            canComment: (bool)$data['canComment'],
            role: $data['role'],
            boardId: $data['boardId']
        );
    }
}