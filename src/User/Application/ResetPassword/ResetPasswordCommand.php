<?php

declare(strict_types=1);

namespace App\User\Application\ResetPassword;

use Symfony\Component\Messenger\Attribute\AsMessage;

#[AsMessage]
class ResetPasswordCommand
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
