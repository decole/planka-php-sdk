<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;
use Planka\Bridge\Contracts\Actions\ResponseResultInterface;
use Planka\Bridge\Contracts\Factory\OutputInterface;
use Planka\Bridge\Views\Factory\Common\ServerInfoDtoFactory;

final class GetInfoAction implements ActionInterface, ResponseResultInterface
{
    public function url(): string
    {
        return '';
    }

    public function getOptions(): array
    {
        return [];
    }

    public function getFactory(): OutputInterface
    {
        return new ServerInfoDtoFactory();
    }
}
