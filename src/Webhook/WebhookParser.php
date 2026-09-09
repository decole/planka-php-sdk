<?php

declare(strict_types=1);

namespace Planka\Bridge\Webhook;

use Planka\Bridge\Views\Factory\Board\BoardItemDtoFactory;
use Planka\Bridge\Views\Factory\Card\CardActionItemDtoFactory;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;

final class WebhookParser
{
    private readonly CardActionItemDtoFactory $cardActionFactory;

    private readonly CardDtoFactory $cardFactory;

    private readonly BoardItemDtoFactory $boardFactory;

    private readonly ProjectDtoFactory $projectFactory;

    private readonly UserDtoFactory $userFactory;

    public function __construct()
    {
        $this->cardActionFactory = new CardActionItemDtoFactory();
        $this->cardFactory = new CardDtoFactory();
        $this->boardFactory = new BoardItemDtoFactory();
        $this->projectFactory = new ProjectDtoFactory();
        $this->userFactory = new UserDtoFactory();
    }

    /**
     * Parses incoming Webhook payload from Planka server.
     *
     * @param string|array<string, mixed> $payload Raw JSON string or decoded associative array
     */
    public function parse(string|array $payload): WebhookEventDto
    {
        $data = is_string($payload) ? json_decode($payload, true) : $payload;

        if (!is_array($data)) {
            throw new \InvalidArgumentException('Invalid Webhook payload: expecting JSON string or array.');
        }

        $eventType = (string) ($data['action']['type'] ?? $data['type'] ?? $data['event'] ?? 'unknown');

        $action = isset($data['action']) && is_array($data['action'])
            ? $this->cardActionFactory->create($data['action'])
            : null;

        $card = isset($data['card']) && is_array($data['card'])
            ? $this->cardFactory->create($data['card'])
            : null;

        $board = isset($data['board']) && is_array($data['board'])
            ? $this->boardFactory->create($data['board'])
            : null;

        $project = isset($data['project']) && is_array($data['project'])
            ? $this->projectFactory->create($data['project'])
            : null;

        $user = isset($data['user']) && is_array($data['user'])
            ? $this->userFactory->create($data['user'])
            : null;

        return new WebhookEventDto(
            eventType: $eventType,
            action: $action,
            card: $card,
            board: $board,
            project: $project,
            user: $user,
            rawPayload: $data,
        );
    }
}
