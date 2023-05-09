<?php

namespace Planka\Bridge\Contracts\Actions;

interface AuthenticateInterface
{
    public function getToken(): string;
}