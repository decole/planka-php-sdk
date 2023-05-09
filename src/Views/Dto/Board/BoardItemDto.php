<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Board;

use DateTimeImmutable;
use Planka\Bridge\Contracts\Dto\OutputDtoInterface;

class BoardItemDto implements OutputDtoInterface
{
    // "item": {
    //        "id": "745435921242915851",
    //        "createdAt": "2022-06-24T07:08:06.000Z",
    //        "updatedAt": null,
    //        "position": 131070,
    //        "name": "host.com",
    //        "projectId": "744759349448016899"
    //    },
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $projectId,
        public readonly ?int $position,
        public readonly ?string $name,
        public readonly ?DateTimeImmutable $createdAt,
        public readonly ?DateTimeImmutable $updatedAt = null,
    ) {
    }
}