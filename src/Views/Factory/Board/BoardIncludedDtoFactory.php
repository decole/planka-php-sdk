<?php

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardIncludedDto;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use function Fp\Collection\map;

final class BoardIncludedDtoFactory implements OutputInterface
{
    public function create(array $data): BoardIncludedDto
    {
        $included = $data['included'] ?? [];

        return new BoardIncludedDto(
            users: $this->getUsers($included),
            boardMemberships: $this->getBoardMemberships($included),
            labels: $this->getLabels($included),
            lists: $this->getLists($included),
            cards: $this->getCards($included),
            cardMemberships: $this->getCardMemberships($included),
            cardLabels: $this->getCardLabels($included),
            tasks: $this->getTasks($included),
            attachments: $this->getAttachments($included),
            projects: $this->getProjects($included)
        );
    }

    private function getUsers(array $data): array
    {
        return map($data['users'] ?? [], fn(array $item) => (new UserDtoFactory())->create($item));
    }

    private function getBoardMemberships(array $data): array
    {
        return [];
    }

    private function getLabels(array $data): array
    {
        return [];
    }

    private function getLists(array $data): array
    {
        return [];
    }

    private function getCards(array $data): array
    {
        return [];
    }

    private function getCardMemberships(array $data): array
    {
        return [];
    }

    private function getCardLabels(array $data): array
    {
        return [];
    }

    private function getTasks(array $data): array
    {
        return [];
    }

    private function getAttachments(array $data): array
    {
        return [];
    }

    private function getProjects(array $data): array
    {
        return [];
    }
}