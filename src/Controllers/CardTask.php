<?php

declare(strict_types=1);

namespace Planka\Bridge\Controllers;

use Planka\Bridge\Actions\CardTask\CardTaskCreateAction;
use Planka\Bridge\Actions\CardTask\CardTaskDeleteAction;
use Planka\Bridge\Actions\CardTask\CardTaskUpdateAction;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\TransportClients\Client;
use Planka\Bridge\Config;

final class CardTask
{
    public function __construct(
        private readonly Config $config,
        private readonly Client $client
    ) {
    }

    /** 'POST /api/cards/:cardId/tasks' */
    public function create(string $cardId, string $name, int $position): CardTaskDto
    {
        return $this->client->post(new CardTaskCreateAction(
            cardId: $cardId,
            name: $name,
            position: $position,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'PATCH /api/tasks/:id' */
    public function update(CardTaskDto $task): CardTaskDto
    {
        return $this->client->patch(new CardTaskUpdateAction(
            task: $task,
            token: $this->config->getAuthToken()
        ));
    }

    /** 'DELETE /api/tasks/:id' */
    public function delete(string $taskId): CardTaskDto
    {
        return $this->client->delete(new CardTaskDeleteAction(taskId: $taskId, token: $this->config->getAuthToken()));
    }
}