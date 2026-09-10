<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Enum\BoardMembershipRoleEnum;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;

interface BoardMembershipResourceInterface
{
    public function add(string $boardId, string $userId, BoardMembershipRoleEnum $role): BoardMembershipDto;

    public function update(
        string $membershipId,
        BoardMembershipRoleEnum $role,
        bool $canComment = true,
    ): BoardMembershipDto;

    /**
     * @param array{role?: 'editor'|'viewer'|BoardMembershipRoleEnum, canComment?: bool|null} $map
     */
    public function patching(string $membershipId, array $map): BoardMembershipDto;

    public function delete(string $membership): BoardMembershipDto;
}
