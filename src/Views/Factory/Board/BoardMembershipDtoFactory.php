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
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     role: string,
     *     canComment: ?bool,
     *     boardId: string,
     *     userId: string,
     *     projectId?: ?string
     * }
     */
    public function create(array $data): BoardMembershipDto
    {
        $data = $data['item'] ?? $data;

        return new BoardMembershipDto(
            id: (string) $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            userId: (string) $data['userId'],
            canComment: (bool) ($data['canComment'] ?? false),
            role: BoardMembershipRoleEnum::from((string) $data['role']),
            boardId: (string) $data['boardId'],
            projectId: isset($data['projectId']) && is_string($data['projectId']) ? $data['projectId'] : null,
            _rawResponse: $data,
        );
    }
}
