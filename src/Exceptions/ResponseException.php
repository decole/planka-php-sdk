<?php

declare(strict_types=1);

namespace Planka\Bridge\Exceptions;

class ResponseException extends \Exception implements PlankaSdkExceptionInterface
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    final public function getStatusCode(): int
    {
        return $this->getCode();
    }
}
