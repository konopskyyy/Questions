<?php

declare(strict_types=1);

namespace App\User\Application\Deactivate;

use App\Common\Middleware\AsMessageValidator;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\UserRepository;

#[AsMessageValidator]
class DeactivateUserCommandValidator
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(DeactivateUserCommand $command): void
    {
        /** @var User $user */
        $user = $this->userRepository->findById($command->userId);

        if ($user) {
            throw new \DomainException('Użytkownik nie istnieje.');
        }

        if (!$user->isActive()) {
            throw new \DomainException('Użytkownik jest już nieaktywny.');
        }
    }
}
