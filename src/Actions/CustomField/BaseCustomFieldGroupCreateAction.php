<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;
use Planka\Bridge\Views\Factory\CustomField\BaseCustomFieldGroupDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class BaseCustomFieldGroupCreateAction implements ActionInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $projectId,
        string $name,
    ) {
        $this->options['json'] = ['name' => $name];
    }

    public function url(): string
    {
        return "api/projects/{$this->projectId}/base-custom-field-groups";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hydrate(ResponseInterface $response): BaseCustomFieldGroupDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new BaseCustomFieldGroupDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
