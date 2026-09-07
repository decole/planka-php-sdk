<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\BoardMembership\BoardMembershipAddAction;
use Planka\Bridge\Actions\BoardMembership\BoardMembershipDeleteAction;
use Planka\Bridge\Actions\BoardMembership\BoardMembershipUpdateAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Config;
use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\Traits\BoardMembershipHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;

final class BoardMembership
{
    use BoardMembershipHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/boards/:boardId/memberships' */
    public function add(string $boardId, string $userId, BoardMembershipRoleEnum $role): BoardMembershipDto
    {
        return $this->client->post(new BoardMembershipAddAction(
            boardId: $boardId,
            userId: $userId,
            role: $role,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'PATCH /api/board-memberships/:id' */
    public function update(
        string $membershipId,
        BoardMembershipRoleEnum $role,
        bool $canComment = true,
    ): BoardMembershipDto {
        return $this->client->patch(new BoardMembershipUpdateAction(
            membershipId: $membershipId,
            role: $role,
            token: $this->config->getAuthToken(),
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
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'DELETE /api/board-memberships/:id' */
    public function delete(string $membership): BoardMembershipDto
    {
        return $this->client->delete(new BoardMembershipDeleteAction(
            membership: $membership,
            token: $this->config->getAuthToken(),
        ));
    }
}
