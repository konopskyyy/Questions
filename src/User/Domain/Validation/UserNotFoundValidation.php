<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

use App\User\Domain\User;

class UserNotFoundValidation implements UserValidationInterface
{
    private const string MESSAGE = 'U01: User was not found.';

    public function validate(?User $user): void
    {
        if (!$user) {
            throw new \DomainException(self::MESSAGE);
        }
    }
}
