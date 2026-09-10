<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Label;

use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Traits\DateConverterTrait;
use Planka\Bridge\Views\Dto\Label\LabelDto;
use Planka\Bridge\Enum\LabelColorEnum;

final class LabelDtoFactory implements OutputInterface
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
     *     position: int,
     *     name: string,
     *     color: ?string,
     *     boardId: string
     * }
     */
    public function create(array $data): LabelDto
    {
        $data = $data['item'] ?? $data;

        return new LabelDto(
            id: (string) $data['id'],
            boardId: (string) $data['boardId'],
            createdAt: $this->convertToDateTime($data['createdAt'] ?? null) ?? new \DateTimeImmutable(),
            updatedAt: $this->convertToDateTime($data['updatedAt'] ?? null),
            position: (int) ($data['position'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            color: isset($data['color']) && is_string($data['color']) ? LabelColorEnum::tryFrom($data['color']) : null,
            _rawResponse: $data,
        );
    }
}
