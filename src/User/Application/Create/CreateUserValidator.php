<?php

declare(strict_types=1);

namespace App\User\Application\Create;

use App\Common\Attribute\AsMessageValidator;
use App\User\Infrastructure\Repository\UserRepository;

#[AsMessageValidator]
class CreateUserValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = $this->userRepository->findByEmail($command->email);

        if ($user) {
            throw new \DomainException('Użytkownik o podanym emailu już istnieje.');
        }
    }
}
