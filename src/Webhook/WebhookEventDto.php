<?php

declare(strict_types=1);

namespace Planka\Bridge\Webhook;

use Planka\Bridge\Views\Dto\Action\ActionDto;
use Planka\Bridge\Views\Dto\Board\BoardItemDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\User\UserDto;

final class WebhookEventDto
{
    /**
     * @param array<string, mixed> $rawPayload
     */
    public function __construct(
        public readonly string $eventType,
        public readonly ?ActionDto $action = null,
        public readonly ?CardDto $card = null,
        public readonly ?BoardItemDto $board = null,
        public readonly ?ProjectDto $project = null,
        public readonly ?UserDto $user = null,
        public readonly array $rawPayload = [],
    ) {}

    public function isCardCreated(): bool
    {
        return 'cardCreate' === $this->eventType || 'createCard' === $this->eventType;
    }

    public function isCardMoved(): bool
    {
        return 'cardMove' === $this->eventType || 'moveCard' === $this->eventType;
    }

    public function isCardUpdated(): bool
    {
        return 'cardUpdate' === $this->eventType || 'updateCard' === $this->eventType;
    }

    public function isCardDeleted(): bool
    {
        return 'cardDelete' === $this->eventType || 'deleteCard' === $this->eventType;
    }

    public function isCommentCreated(): bool
    {
        return 'commentCreate' === $this->eventType || 'createComment' === $this->eventType;
    }
}
