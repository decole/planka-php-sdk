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
     * @param array{
     *     id: string,
     *     createdAt: string,
     *     updatedAt: ?string,
     *     position: int,
     *     name: string,
     *     color: ?string,
     *     boardId: string
     * } $data
     * @return LabelDto
     */
    public function create(array $data): LabelDto
    {
        return new LabelDto(
            id: $data['id'],
            boardId: $data['boardId'],
            createdAt: $this->convertToDateTime($data['createdAt']),
            updatedAt: $this->convertToDateTime($data['updatedAt']),
            position: (int)$data['position'],
            name: $data['name'],
            color: LabelColorEnum::tryFrom($data['color'])
        );
    }
}