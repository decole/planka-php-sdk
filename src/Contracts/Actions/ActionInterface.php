<?php

namespace Planka\Bridge\Contracts\Actions;

interface ActionInterface
{
    public function url(): string;

    public function getOptions(): array;
}