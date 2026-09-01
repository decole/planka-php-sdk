<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldGroupDto;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldGroupDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CustomFieldGroupDeleteAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(private readonly string $id) {}

    public function url(): string
    {
        return "api/custom-field-groups/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
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
