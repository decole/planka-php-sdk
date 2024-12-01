<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardMembershipDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class CardMembershipDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     cardId: string,
     *     userId: string
     * } $data
     */
    public function create(array $data): CardMembershipDto
    {
        return new CardMembershipDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            cardId: $data['cardId'],
            userId: $data['userId'],
        );
    }
}
