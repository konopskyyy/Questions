<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

use App\User\Domain\User;

class UserIsNotAdminValidation implements UserValidationInterface
{
    private const string MESSAGE = 'U05: User is not an admin.';

    public function validate(?User $user, ValidationContext $context): void
    {
        if ($user && !$user->isAdmin()) {
            throw new UserValidationException(self::MESSAGE, $context);
        }
    }
}
