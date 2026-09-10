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
     * @param array<string, mixed> $data
     *
     * @see Payload structure:
     * array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     cardId: string,
     *     labelId: ?string
     * }
     */
    public function create(array $data): CardLabelDto
    {
        $data = $data['item'] ?? $data;

        return new CardLabelDto(
            id: (string) $data['id'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            cardId: (string) $data['cardId'],
            labelId: isset($data['labelId']) && is_string($data['labelId']) ? $data['labelId'] : null,
            _rawResponse: $data,
        );
    }
}
