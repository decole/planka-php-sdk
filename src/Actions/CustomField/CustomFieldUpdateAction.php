<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Exceptions\ResponseException;
use Planka\Bridge\Views\Dto\CustomField\CustomFieldDto;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldDtoFactory;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class CustomFieldUpdateAction implements ActionInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $id,
        ?string $name = null,
        ?int $position = null,
        ?bool $showOnFrontOfCard = null,
    ) {
        $body = [];

        if (null !== $name) {
            $body['name'] = $name;
        }

        if (null !== $position) {
            $body['position'] = $position;
        }

        if (null !== $showOnFrontOfCard) {
            $body['showOnFrontOfCard'] = $showOnFrontOfCard;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/custom-fields/{$this->id}";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function hydrate(ResponseInterface $response): CustomFieldDto
    {
        $result = $response->toArray();

        if (array_key_exists('item', $result)) {
            return (new CustomFieldDtoFactory())->create($result['item']);
        }

        throw new ResponseException($response->getContent());
    }
}
