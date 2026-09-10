<?php

declare(strict_types=1);

namespace Planka\Bridge\Views\Factory\Project;

use Planka\Bridge\Views\Dto\Project\ProjectIncludedDto;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Dto\Project\ProjectListDto;
use Planka\Bridge\Views\Dto\Project\ProjectDto;

use function Fp\Collection\map;

final class ProjectListDtoFactory implements OutputInterface
{
    /**
     * @see Payload structure:
     * array{
     *     items: array,
     *     included: array
     * }
     */
    public function create(array $data): ProjectListDto
    {
        return new ProjectListDto(
            items: $this->getItems($data),
            included: $this->getIncluded($data),
            _rawResponse: $data,
        );
    }

    /**
     * @return list<ProjectDto>
     */
    private function getItems(array $data): array
    {
        return map(
            $data['items'] ?? [],
            fn(array $item) => (new ProjectDtoFactory())->create($item),
        );
    }

    private function getIncluded(array $data): ProjectIncludedDto
    {
        $included = [];

        if (isset($data['included']) && is_array($data['included'])) {
            $included = $data['included'];
        }

        return (new ProjectIncludedDtoFactory())->create($included);
    }
}
