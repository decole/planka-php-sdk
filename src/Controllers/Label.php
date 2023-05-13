<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Label\LabelCreateAction;
use Planka\Bridge\Actions\Label\LabelDeleteAction;
use Planka\Bridge\Actions\Label\LabelUpdateAction;
use Planka\Bridge\Views\Dto\Label\LabelDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Config;

final class Label
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/boards/:boardId/labels' */
    public function create(string $boardId, string $name, LabelColorEnum $color, int $position): LabelDto
    {
        return $this->client->post(new LabelCreateAction(
            token: $this->config->getAuthToken(),
            boardId: $boardId,
            name: $name,
            color: $color,
            position: $position
        ));
    }

    /** 'PATCH /api/labels/:id'*/
    public function update(string $labelId, string $name, LabelColorEnum $color): LabelDto
    {
        return $this->client->patch(new LabelUpdateAction(
            token: $this->config->getAuthToken(),
            labelId: $labelId,
            name: $name,
            color: $color
        ));
    }

    /** 'DELETE /api/labels/:id' */
    public function delete(string $labelId): LabelDto
    {
        return $this->client->delete(new LabelDeleteAction(token: $this->config->getAuthToken(), labelId: $labelId));
    }
}