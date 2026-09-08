<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\CustomField;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\CustomField\CustomFieldGroupDtoFactory;

final class CustomFieldGroupUpdateAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        private readonly string $customFieldGroupId,
        array $data,
    ) {
        $this->options['json'] = $data;
    }

    public function url(): string
    {
        return "api/custom-field-groups/{$this->customFieldGroupId}";
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
