<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\Common\CommonPatchAction;
use Planka\Bridge\Actions\Label\LabelCreateAction;
use Planka\Bridge\Actions\Label\LabelDeleteAction;
use Planka\Bridge\Actions\Label\LabelUpdateAction;
use Planka\Bridge\Config;
use Planka\Bridge\Enum\LabelColorEnum;
use Planka\Bridge\Traits\LabelHydrateTrait;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Views\Dto\Label\LabelDto;

final class Label
{
    use LabelHydrateTrait;

    public function __construct(
        private readonly Config $config,
        private readonly Client $client,
    ) {}

    /** 'POST /api/boards/:boardId/labels' */
    public function create(string $boardId, string $name, LabelColorEnum $color, int $position): LabelDto
    {
        return $this->client->post(new LabelCreateAction(
            boardId: $boardId,
            name: $name,
            color: $color,
            position: $position,
            token: $this->config->getAuthToken(),
        ));
    }

    /** 'PATCH /api/labels/:id'*/
    public function update(string $labelId, string $name, LabelColorEnum $color): LabelDto
    {
        return $this->client->patch(new LabelUpdateAction(
            labelId: $labelId,
            name: $name,
            color: $color,
            token: $this->config->getAuthToken(),
        ));
    }

    /**
     * 'PATCH /api/labels/:id' - Partially updates label properties.
     *
     * @param string $labelId Label ID
     * @param array{
     *   name?: string|null,
     *   color?: string|LabelColorEnum,
     *   position?: int
     * } $map Associative array of fields to update
     */
    public function patching(string $labelId, array $map): LabelDto
    {
        if (isset($map['color']) && $map['color'] instanceof LabelColorEnum) {
            $map['color'] = $map['color']->value;
        }

        return $this->client->patch(new CommonPatchAction(
            urlPath: "api/labels/{$labelId}",
            data: $map,
            hydrateCallback: fn($response) => $this->hydrate($response),
        ));
    }

    /** 'DELETE /api/labels/:id' */
    public function delete(string $labelId): LabelDto
    {
        return $this->client->delete(new LabelDeleteAction(labelId: $labelId, token: $this->config->getAuthToken()));
    }
}
