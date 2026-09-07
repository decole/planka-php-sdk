<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\CustomField\CustomFieldCreateInBaseGroupAction;
use Planka\Bridge\Actions\CustomField\CustomFieldCreateInGroupAction;
use Planka\Bridge\Actions\CustomField\CustomFieldDeleteAction;
use Planka\Bridge\Actions\CustomField\CustomFieldUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldDtoFactory;

final class CustomField
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/base-custom-field-groups/:baseGroupId/custom-fields' */
    public function createInBaseGroup(string $baseGroupId, string $name, int $position = 65536, ?bool $showOnFrontOfCard = null): CustomFieldDto
    {
        return $this->client->post(new CustomFieldCreateInBaseGroupAction(
            baseGroupId: $baseGroupId,
            name: $name,
            position: $position,
            showOnFrontOfCard: $showOnFrontOfCard,
        ));
    }

    /** 'POST /api/custom-field-groups/:groupId/custom-fields' */
    public function createInGroup(string $groupId, string $name, int $position = 65536, ?bool $showOnFrontOfCard = null): CustomFieldDto
    {
        return $this->client->post(new CustomFieldCreateInGroupAction(
            groupId: $groupId,
            name: $name,
            position: $position,
            showOnFrontOfCard: $showOnFrontOfCard,
        ));
    }

    /** 'PATCH /api/custom-fields/:id' */
    public function update(string $id, ?string $name = null, ?int $position = null, ?bool $showOnFrontOfCard = null): CustomFieldDto
    {
        return $this->client->patch(new CustomFieldUpdateAction(
            id: $id,
            name: $name,
            position: $position,
            showOnFrontOfCard: $showOnFrontOfCard,
        ));
    }

    /**
     * 'PATCH /api/custom-fields/:id' - Partially updates custom field properties.
     *
     * @param string $id Custom field ID
     * @param array{
     *   name?: string,
     *   position?: int,
     *   showOnFrontOfCard?: bool
     * } $map Associative array of fields to update
     */
    public function patching(string $id, array $map): CustomFieldDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/custom-fields/{$id}",
            data: $map,
            hydrateCallback: function ($response): CustomFieldDto {
                $result = $response->toArray();

                if (array_key_exists('item', $result)) {
                    return (new CustomFieldDtoFactory())->create($result['item']);
                }

                throw new ResponseException($response->getContent());
            },
        ));
    }

    /** 'DELETE /api/custom-fields/:id' */
    public function delete(string $id): CustomFieldDto
    {
        return $this->client->delete(new CustomFieldDeleteAction(id: $id));
    }
}
