<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\CustomField\BaseCustomFieldGroupDtoFactory;

final class BaseCustomFieldGroupCreateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
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

    public function getFactory(): OutputInterface
    {
        return new BaseCustomFieldGroupDtoFactory();
    }
}
