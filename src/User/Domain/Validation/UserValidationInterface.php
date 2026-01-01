<?php

declare(strict_types=1);

namespace App\User\Domain\Validation;

use App\User\Domain\User;

interface UserValidationInterface
{
    public function validate(?User $user): void;
}
