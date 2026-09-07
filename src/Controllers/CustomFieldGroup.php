<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupCreateInBoardAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupCreateInCardAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupDeleteAction;
use Planka\Bridge\Actions\CustomField\CustomFieldGroupUpdateAction;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldGroupDtoFactory;

final class CustomFieldGroup
{
    public function __construct(
        private readonly TransportClientInterface $client,
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
        $data = [];

        if (null !== $name) {
            $data['name'] = $name;
        }

        if (null !== $position) {
            $data['position'] = $position;
        }

        return $this->client->patch(new CustomFieldGroupUpdateAction(
            customFieldGroupId: $id,
            data: $data,
        ));
    }

    /**
     * 'PATCH /api/custom-field-groups/:id' - Partially updates custom field group properties.
     *
     * @param string $id Custom field group ID
     * @param array{
     *   name?: string|null,
     *   position?: int
     * } $map Associative array of fields to update
     */
    public function patching(string $id, array $map): CustomFieldGroupDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/custom-field-groups/{$id}",
            data: $map,
            hydrateCallback: new CustomFieldGroupDtoFactory(),
        ));
    }

    /** 'DELETE /api/custom-field-groups/:id' */
    public function delete(string $id): CustomFieldGroupDto
    {
        return $this->client->delete(new CustomFieldGroupDeleteAction(customFieldGroupId: $id));
    }
}
