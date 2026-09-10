<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Dto\Project;

use Planka\Bridge\Contracts\Dto\OutputDtoInterface;
use Planka\Bridge\Traits\OutputDtoTrait;

final class ProjectListDto implements OutputDtoInterface
{
    use OutputDtoTrait;

    /**
     * @param list<ProjectDto> $items
     */
    public function __construct(
        public readonly array $items,
        public readonly ProjectIncludedDto $included,
        /** @var array<string, mixed> Diagnostic raw response array from Planka API to verify DTO field hydration. */
        public readonly array $_rawResponse = [],
    ) {}
}
