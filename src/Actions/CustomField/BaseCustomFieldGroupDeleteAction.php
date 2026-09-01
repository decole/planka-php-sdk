<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\CustomField\BaseCustomFieldGroupDto;
use Planka\Bridge\Views\Factory\CustomField\BaseCustomFieldGroupDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class BaseCustomFieldGroupDeleteAction implements ActionInterface, ResponseResultInterface
{
    public function __construct(private readonly string $id) {}

    public function url(): string
    {
        return "api/base-custom-field-groups/{$this->id}";
    }

    public function getOptions(): array
    {
        return [];
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
