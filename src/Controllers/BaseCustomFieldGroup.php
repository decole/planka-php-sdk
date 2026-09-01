<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CustomField\BaseCustomFieldGroupCreateAction;
use Planka\Bridge\Actions\CustomField\BaseCustomFieldGroupDeleteAction;
use Planka\Bridge\Actions\CustomField\BaseCustomFieldGroupUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;

final class BaseCustomFieldGroup
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

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

    /** 'DELETE /api/base-custom-field-groups/:id' */
    public function delete(string $id): BaseCustomFieldGroupDto
    {
        return $this->client->delete(new BaseCustomFieldGroupDeleteAction(id: $id));
    }
}
