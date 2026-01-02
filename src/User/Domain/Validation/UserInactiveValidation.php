<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

use App\User\Domain\User;

class UserInactiveValidation implements UserValidationInterface
{
    private const string MESSAGE = 'U02: User is inactive.';

    public function validate(?User $user): void
    {
        if ($user && !$user->isActive()) {
            throw new \DomainException(self::MESSAGE);
        }
    }
}
