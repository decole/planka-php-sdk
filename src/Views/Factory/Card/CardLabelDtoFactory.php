<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Card;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Card\CardLabelDto;
use Planka\Bridge\Traits\DateConverterTrait;

final class CardLabelDtoFactory implements OutputInterface
{
    use DateConverterTrait;

    /**
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     cardId: string,
     *     labelId: ?string
     * } $data
     */
    public function create(array $data): CardLabelDto
    {
        return new CardLabelDto(
            id: $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            cardId: $data['cardId'],
            labelId: $data['labelId'],
        );
    }
}
