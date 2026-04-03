<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

class UserValidationException extends \DomainException
{
    public function __construct(
        string $message,
        private readonly ValidationContext $context,
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getContext(): ValidationContext
    {
        return $this->context;
    }
}
