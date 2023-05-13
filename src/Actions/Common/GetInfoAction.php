<?php

declare(strict_types=1);

namespace Planka\Bridge\Actions\Common;

use Planka\Bridge\Contracts\Actions\ActionInterface;

final class GetInfoAction implements ActionInterface
{
    public function url(): string
    {
        return '';
    }

    public function getOptions(): array
    {
        return [];
    }
}