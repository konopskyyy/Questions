<?php

declare(strict_types=1);

namespace App\User\Application\Create;

class CreateUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
