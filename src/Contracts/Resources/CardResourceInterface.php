<?php

declare(strict_types=1);

namespace Planka\Bridge\Contracts\Resources;

use Planka\Bridge\Builders\CardBuilder;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Inputs\CardCreateInput;
use Planka\Bridge\Inputs\CardPatchInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;

interface CardResourceInterface
{
    public function builder(?string $name = null): CardBuilder;

    public function create(
        string $listId,
        string|CardCreateInput|CardBuilder $nameOrInput,
        int $position = 65536,
        BoardDefaultCardTypeEnum $type = BoardDefaultCardTypeEnum::PROJECT,
    ): CardDto;

    public function get(string $cardId): CardDto;

    public function update(CardDto $card): CardDto;

    /**
     * @param array<string, mixed>|CardPatchInput|PatchInputInterface $map
     */
    public function patching(string $cardId, array|PatchInputInterface $map): CardDto;

    public function clearTime(CardDto $card): CardDto;

    public function moveCard(CardDto $card): CardDto;

    public function addSpentTime(CardDto $card, int $seconds): CardDto;

    public function triggerTimer(CardDto $card, bool $start): CardDto;

    public function delete(string $cardId): void;

    public function subscribe(string $cardId, string $userId): CardMembershipDto;

    public function unsubscribe(string $cardId, string $userId): CardMembershipDto;

    public function duplicate(string $cardId, ?string $listId = null, int $position = 65536): CardDto;

    public function readNotifications(string $cardId): CardDto;
}
