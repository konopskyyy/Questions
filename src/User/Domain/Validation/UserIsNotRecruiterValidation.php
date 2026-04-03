<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

use App\User\Domain\User;

class UserIsNotRecruiterValidation implements UserValidationInterface
{
    private const string MESSAGE = 'U04: User is not a recruiter.';

    public function validate(?User $user, ValidationContext $context): void
    {
        if ($user && !$user->isRecruiter()) {
            throw new UserValidationException(self::MESSAGE, $context);
        }
    }
}
