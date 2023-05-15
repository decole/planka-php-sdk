<?php

declare(strict_types = 1);

namespace Planka\Bridge\Views\Factory\Board;

use Planka\Bridge\Views\Factory\Attachment\AttachmentDtoFactory;
use Planka\Bridge\Views\Factory\Card\CardMembershipDtoFactory;
use Planka\Bridge\Views\Factory\Project\ProjectDtoFactory;
use Planka\Bridge\Views\Factory\Card\CardLabelDtoFactory;
use Planka\Bridge\Views\Factory\Card\CardTaskDtoFactory;
use Planka\Bridge\Views\Factory\Label\LabelDtoFactory;
use Planka\Bridge\Views\Dto\Attachment\AttachmentDto;
use Planka\Bridge\Views\Dto\Board\BoardMembershipDto;
use Planka\Bridge\Views\Factory\Card\CardDtoFactory;
use Planka\Bridge\Views\Factory\List\ListDtoFactory;
use Planka\Bridge\Views\Factory\User\UserDtoFactory;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Board\BoardIncludedDto;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;
use Planka\Bridge\Views\Dto\Card\CardLabelDto;
use Planka\Bridge\Views\Dto\Card\CardTaskDto;
use Planka\Bridge\Views\Dto\Label\LabelDto;
use Planka\Bridge\Views\Dto\Card\CardDto;
use Planka\Bridge\Views\Dto\List\ListDto;
use Planka\Bridge\Views\Dto\User\UserDto;
use function Fp\Collection\map;

final class BoardIncludedDtoFactory implements OutputInterface
{
    /**
     * @param array{
     *     users: array,
     *     boardMemberships: array,
     *     labels: array,
     *     lists: array,
     *     cards: array,
     *     cardMemberships: array,
     *     cardLabels: array,
     *     tasks: array,
     *     attachments: array{
     *         id: string,
     *         createdAt: string,
     *         updatedAt: ?string,
     *         name: string,
     *         cardId: string,
     *         url: string,
     *         coverUrl: ?string,
     *         creatorUserId: string,
     *         image: array{height: int, width: int}
     *     }|null,
     *     projects: array
     * }|null $data
     * @return BoardIncludedDto|null
     */
    public function create(?array $data): ?BoardIncludedDto
    {
        if ($data === null) {
            return null;
        }

        return new BoardIncludedDto(
            users: $this->getUsers($data),
            boardMemberships: $this->getBoardMemberships($data),
            labels: $this->getLabels($data),
            lists: $this->getLists($data),
            cards: $this->getCards($data),
            cardMemberships: $this->getCardMemberships($data),
            cardLabels: $this->getCardLabels($data),
            tasks: $this->getTasks($data),
            attachments: $this->getAttachments($data),
            projects: $this->getProjects($data)
        );
    }

    /**
     * @return list<UserDto>
     */
    private function getUsers(array $data): array
    {
        return map($data['users'] ?? [], fn(array $item) => (new UserDtoFactory())->create($item));
    }

    /**
     * @return list<BoardMembershipDto>
     */
    private function getBoardMemberships(array $data): array
    {
        return map($data['boardMemberships'] ?? [],
            fn(array $item) => (new BoardMembershipDtoFactory())->create($item)
        );
    }

    /**
     * @return list<LabelDto>
     */
    private function getLabels(array $data): array
    {
        return map($data['labels'] ?? [],
            fn(array $item) => (new LabelDtoFactory())->create($item)
        );
    }

    /**
     * @return list<ListDto>
     */
    private function getLists(array $data): array
    {
        return map($data['lists'] ?? [],
            fn(array $item) => (new ListDtoFactory())->create($item)
        );
    }

    /**
     * @return list<CardDto>
     */
    private function getCards(array $data): array
    {
        return map($data['cards'] ?? [],
            fn(array $item) => (new CardDtoFactory())->create($item)
        );
    }

    /**
     * @return list<CardMembershipDto>
     */
    private function getCardMemberships(array $data): array
    {
        return map($data['cardMemberships'] ?? [],
            fn(array $item) => (new CardMembershipDtoFactory())->create($item)
        );
    }

    /**
     * @return list<CardLabelDto>
     */
    private function getCardLabels(array $data): array
    {
        return map($data['cardLabels'] ?? [],
            fn(array $item) => (new CardLabelDtoFactory())->create($item)
        );
    }

    /**
     * @return list<CardTaskDto>
     */
    private function getTasks(array $data): array
    {
        return map($data['tasks'] ?? [],
            fn(array $item) => (new CardTaskDtoFactory())->create($item)
        );
    }

    /**
     * @return list<AttachmentDto>|array
     */
    private function getAttachments(array $data): array
    {
        return map($data['attachments'] ?? [],
            fn(array $item) => (new AttachmentDtoFactory())->create($item)
        );
    }

    /**
     * @return list<ProjectDto>
     */
    private function getProjects(array $data): array
    {
        return map($data['projects'] ?? [],
            fn(array $item) => (new ProjectDtoFactory())->create($item)
        );
    }
}