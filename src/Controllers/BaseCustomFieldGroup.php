<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\CustomField\BaseCustomFieldGroupCreateAction;
use Planka\Bridge\Actions\CustomField\BaseCustomFieldGroupDeleteAction;
use Planka\Bridge\Actions\CustomField\BaseCustomFieldGroupUpdateAction;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\TransportClients\TransportClientInterface;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;
use Planka\Bridge\Views\Factory\CustomField\BaseCustomFieldGroupDtoFactory;

final class BaseCustomFieldGroup
{
    public function __construct(private readonly TransportClientInterface $client) {}

    /** 'POST /api/projects/:projectId/base-custom-field-groups' */
    public function create(string $projectId, string $name): BaseCustomFieldGroupDto
    {
        return $this->client->post(new BaseCustomFieldGroupCreateAction(projectId: $projectId, name: $name));
    }

    /** 'PATCH /api/base-custom-field-groups/:id' */
    public function update(string $id, string $name): BaseCustomFieldGroupDto
    {
        return $this->client->patch(new BaseCustomFieldGroupUpdateAction(id: $id, name: $name));
    }

    /**
     * 'PATCH /api/base-custom-field-groups/:id' - Partially updates base custom field group properties.
     *
     * @param string $id Base custom field group ID
     * @param array{
     *   name?: string
     * } $map Associative array of fields to update
     */
    public function patching(string $id, array $map): BaseCustomFieldGroupDto
    {
        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/base-custom-field-groups/{$id}",
            data: $map,
            hydrateCallback: function ($response): BaseCustomFieldGroupDto {
                $result = $response->toArray();

                if (array_key_exists('item', $result)) {
                    return (new BaseCustomFieldGroupDtoFactory())->create($result['item']);
                }

                throw new ResponseException($response->getContent());
            },
        ));
    }

    /** 'DELETE /api/base-custom-field-groups/:id' */
    public function delete(string $id): BaseCustomFieldGroupDto
    {
        return $this->client->delete(new BaseCustomFieldGroupDeleteAction(id: $id));
    }
}
