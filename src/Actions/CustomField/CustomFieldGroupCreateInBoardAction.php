<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldGroupDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CustomFieldGroupCreateInBoardAction implements ActionInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $boardId,
        ?string $name = null,
        ?string $baseCustomFieldGroupId = null,
        int $position = 65536,
    ) {
        $body = ['position' => $position];

        if (null !== $name) {
            $body['name'] = $name;
        }

        if (null !== $baseCustomFieldGroupId) {
            $body['baseCustomFieldGroupId'] = $baseCustomFieldGroupId;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/boards/{$this->boardId}/custom-field-groups";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hydrate(ResponseInterface $response): CustomFieldGroupDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new CustomFieldGroupDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
