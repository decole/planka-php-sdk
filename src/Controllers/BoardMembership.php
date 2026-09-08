<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardMembership\BoardMembershipAddAction;
use Planka\Bridge\Actions\BoardMembership\BoardMembershipDeleteAction;
use Planka\Bridge\Actions\BoardMembership\BoardMembershipUpdateAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;
use Planka\Bridge\Views\Factory\Board\BoardMembershipDtoFactory;

final class BoardMembership
{
    public function __construct(
        private readonly TransportClientInterface $client,
    ) {}

    /** 'POST /api/boards/:boardId/memberships' */
    public function add(string $boardId, string $userId, BoardMembershipRoleEnum $role): BoardMembershipDto
    {
        return $this->client->post(new BoardMembershipAddAction(
            boardId: $boardId,
            userId: $userId,
            role: $role,
        ));
    }

    /** 'PATCH /api/board-memberships/:id' */
    public function update(
        string $membershipId,
        BoardMembershipRoleEnum $role,
        bool $canComment = true,
    ): BoardMembershipDto {
        return $this->client->patch(new BoardMembershipUpdateAction(
            boardMembershipId: $membershipId,
            role: $role,
            canComment: $canComment,
        ));
    }

    /**
     * 'PATCH /api/board-memberships/:id' - Partially updates board membership properties.
     *
     * @param string $membershipId Board membership ID
     * @param array{
     *   role?: 'editor'|'viewer'|BoardMembershipRoleEnum,
     *   canComment?: bool|null
     * } $map Associative array of fields to update
     */
    public function patching(string $membershipId, array $map): BoardMembershipDto
    {
        if (isset($map['role']) && $map['role'] instanceof BoardMembershipRoleEnum) {
            $map['role'] = $map['role']->value;
        }

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/board-memberships/{$membershipId}",
            data: $map,
            hydrateCallback: new BoardMembershipDtoFactory(),
        ));
    }

    /** 'DELETE /api/board-memberships/:id' */
    public function delete(string $membership): BoardMembershipDto
    {
        return $this->client->delete(new BoardMembershipDeleteAction(
            boardMembershipId: $membership,
        ));
    }
}
