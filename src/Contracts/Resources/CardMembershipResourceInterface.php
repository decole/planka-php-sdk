<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Views\Dto\Card\CardMembershipDto;

interface CardMembershipResourceInterface
{
    public function add(string $cardId, string $userId): CardMembershipDto;

    public function remove(string $cardId, string $userId): CardMembershipDto;
}
