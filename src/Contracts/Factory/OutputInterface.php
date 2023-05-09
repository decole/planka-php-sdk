<?php

namespace Planka\Bridge\Contracts\Factory;

interface OutputInterface
{
    public function create(array $data): mixed;
}