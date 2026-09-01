<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CustomField\CustomFieldGroupCreateInBoardAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupCreateInCardAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupDeleteAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;

final class CustomFieldGroup
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/boards/:boardId/custom-field-groups' */
    public function createInBoard(string $boardId, ?string $name = null, ?string $baseCustomFieldGroupId = null, int $position = 65536): CustomFieldGroupDto
    {
        return $this->client->post(new CustomFieldGroupCreateInBoardAction(
            boardId: $boardId,
            name: $name,
            baseCustomFieldGroupId: $baseCustomFieldGroupId,
            position: $position,
        ));
    }

    /** 'POST /api/cards/:cardId/custom-field-groups' */
    public function createInCard(string $cardId, ?string $name = null, ?string $baseCustomFieldGroupId = null, int $position = 65536): CustomFieldGroupDto
    {
        return $this->client->post(new CustomFieldGroupCreateInCardAction(
            cardId: $cardId,
            name: $name,
            baseCustomFieldGroupId: $baseCustomFieldGroupId,
            position: $position,
        ));
    }

    /** 'PATCH /api/custom-field-groups/:id' */
    public function update(string $id, ?string $name = null, ?int $position = null): CustomFieldGroupDto
    {
        return $this->client->patch(new CustomFieldGroupUpdateAction(
            id: $id,
            name: $name,
            position: $position,
        ));
    }

    /** 'DELETE /api/custom-field-groups/:id' */
    public function delete(string $id): CustomFieldGroupDto
    {
        return $this->client->delete(new CustomFieldGroupDeleteAction(id: $id));
    }
}
