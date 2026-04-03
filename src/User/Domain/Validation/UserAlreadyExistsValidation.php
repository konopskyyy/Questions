<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

use App\User\Domain\User;

class UserAlreadyExistsValidation implements UserValidationInterface
{
    private const string MESSAGE = 'U03: User already exists.';

    public function validate(?User $user, ValidationContext $context): void
    {
        if ($user) {
            throw new UserValidationException(self::MESSAGE, $context);
        }
    }
}
