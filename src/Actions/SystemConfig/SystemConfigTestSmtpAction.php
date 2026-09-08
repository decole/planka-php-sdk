<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\SystemConfig;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\AuthenticateInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Common\TestResultDtoFactory;

final class SystemConfigTestSmtpAction implements ActionInterface, AuthenticateInterface, ResponseResultInterface
{
    private array $options = [];

    public function __construct(
        string $toEmail,
        ?array $smtpSettings = null,
    ) {
        $body = ['toEmail' => $toEmail];

        if (null !== $smtpSettings) {
            $body = array_merge($body, $smtpSettings);
        }

        $this->options['json'] = $body;
    }

    public function url(): string
    {
        return 'api/system-settings/test-smtp';
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getFactory(): OutputInterface
    {
        return new TestResultDtoFactory();
    }
}
