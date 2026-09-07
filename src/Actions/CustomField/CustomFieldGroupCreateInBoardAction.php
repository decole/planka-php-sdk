<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldGroupDtoFactory;

final class CustomFieldGroupCreateInBoardAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
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

    public function getFactory(): OutputInterface
    {
        return new CustomFieldGroupDtoFactory();
    }
}
