<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldDtoFactory;

final class CustomFieldCreateInGroupAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $groupId,
        string $name,
        int $position = 65536,
        ?bool $showOnFrontOfCard = null,
    ) {
        $body = [
            'name' => $name,
            'position' => $position,
        ];

        if (null !== $showOnFrontOfCard) {
            $body['showOnFrontOfCard'] = $showOnFrontOfCard;
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return "api/custom-field-groups/{$this->groupId}/custom-fields";
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new CustomFieldDtoFactory();
    }
}
