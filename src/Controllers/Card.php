<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Card\CardClearDueDateAction;
use Planka\Bridge\Actions\Card\CardCreateAction;
use Planka\Bridge\Actions\Card\CardDeleteAction;
use Planka\Bridge\Actions\Card\CardDuplicateAction;
use Planka\Bridge\Actions\Card\CardMoveAction;
use Planka\Bridge\Actions\Card\CardReadNotificationsAction;
use Planka\Bridge\Actions\Card\CardSubscribeMembershipAction;
use Planka\Bridge\Actions\Card\CardTimerAction;
use Planka\Bridge\Actions\Card\CardUnsubscribeMembershipAction;
use Planka\Bridge\Actions\Card\CardUpdateAction;
use Planka\Bridge\Actions\Card\CardViewAction;
use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Builders\CardBuilder;
use Planka\Bridge\Config;
use Planka\Bridge\Enum\BoardDefaultCardTypeEnum;
use Planka\Bridge\Inputs\CardCreateInput;
use Planka\Bridge\Inputs\PatchInputInterface;
use Planka\Bridge\Inputs\PatchInputNormalizer;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;

final class Card
{
    public function __construct(private readonly TransportClientInterface $client) {}

    public function builder(?string $name = null): CardBuilder
    {
        return new CardBuilder($name);
    }

    /** 'POST /api/lists/:listId/cards' */
    public function create(
        string $listId,
        string|CardCreateInput|CardBuilder $nameOrInput,
        int $position = 65536,
        BoardDefaultCardTypeEnum $type = BoardDefaultCardTypeEnum::PROJECT,
    ): CardDto {
        if ($nameOrInput instanceof CardBuilder) {
            $nameOrInput = $nameOrInput->build();
        }

        if ($nameOrInput instanceof CardCreateInput) {
            return $this->client->post(new CommonPatchAction(
                urlPath: "api/lists/{$listId}/cards",
                data: $nameOrInput->toArray(),
                hydrateCallback: new CardDtoFactory(),
            ));
        }

        return $this->client->post(new CardCreateAction(
            listId: $listId,
            name: $nameOrInput,
            position: $position,
            type: $type,
        ));
    }

    /** 'GET /api/cards/:id' */
    public function get(string $cardId): CardDto
    {
        return $this->client->get(new CardViewAction(cardId: $cardId));
    }

    /** 'PATCH /api/cards/:id' */
    public function update(CardDto $card): CardDto
    {
        return $this->client->patch(new CardUpdateAction(
            cardId: $card->id,
            data: $card->toArray(),
        ));
    }

    /**
     * 'PATCH /api/cards/:id' - Partially updates card properties.
     *
     * @param string                    $cardId Card ID
     * @param array|PatchInputInterface $map    Associative array or PatchInputInterface of fields to update
     */
    public function patching(string $cardId, array|PatchInputInterface $map): CardDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/cards/{$cardId}",
            data: PatchInputNormalizer::normalize($map),
            hydrateCallback: new CardDtoFactory(),
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function clearTime(CardDto $card): CardDto
    {
        return $this->client->patch(new CardClearDueDateAction(
            cardId: $card->id,
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function moveCard(CardDto $card): CardDto
    {
        return $this->client->patch(new CardMoveAction(
            cardId: $card->id,
            listId: $card->listId,
            position: $card->position,
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function addSpentTime(CardDto $card, int $seconds): CardDto
    {
        $total = ($card->stopwatch->total ?? 0) + $seconds;

        return $this->client->patch(new CardTimerAction(
            cardId: $card->id,
            stopwatch: [
                'startedAt' => $card->stopwatch->startedAt?->format(Config::DATE_FORMAT),
                'total' => $total,
            ],
        ));
    }

    /** 'PATCH /api/cards/:id' */
    public function triggerTimer(CardDto $card, bool $start): CardDto
    {
        return $this->client->patch(new CardTimerAction(
            cardId: $card->id,
            stopwatch: [
                'startedAt' => $start ? (new \DateTimeImmutable())->format(Config::DATE_FORMAT) : null,
                'total' => $card->stopwatch->total ?? 0,
            ],
        ));
    }

    /** 'DELETE /api/cards/:id' */
    public function delete(string $cardId): void
    {
        $this->client->delete(new CardDeleteAction(cardId: $cardId));
    }

    /** 'POST /api/cards/:cardId/memberships' */
    public function subscribe(string $cardId, string $userId): CardMembershipDto
    {
        return $this->client->post(new CardSubscribeMembershipAction(
            cardId: $cardId,
            userId: $userId,
        ));
    }

    /** 'DELETE /api/cards/:cardId/memberships' */
    public function unsubscribe(string $cardId, string $userId): CardMembershipDto
    {
        return $this->client->delete(new CardUnsubscribeMembershipAction(
            cardId: $cardId,
            userId: $userId,
        ));
    }

    /** 'POST /api/cards/:id/duplicate' */
    public function duplicate(
        string $cardId,
        ?string $listId = null,
        int $position = 65536,
    ): CardDto {
        return $this->client->post(new CardDuplicateAction(
            cardId: $cardId,
            listId: $listId,
            position: $position,
        ));
    }

    /** 'POST /api/cards/:id/read-notifications' */
    public function readNotifications(string $cardId): CardDto
    {
        return $this->client->post(new CardReadNotificationsAction(cardId: $cardId));
    }
}
