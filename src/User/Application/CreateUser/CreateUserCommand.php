<?php

namespace App\User\Application\CreateUser;

class CreateUserCommand
{
    public function __construct(
        public string $email,
        public string $password,
    ) {
    }
}
